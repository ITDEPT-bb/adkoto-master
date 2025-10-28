<script setup>
import { ref, onMounted, onBeforeUnmount, defineProps, nextTick } from "vue"; // Added nextTick
import { router, usePage } from "@inertiajs/vue3";
import AgoraRTC from "agora-rtc-sdk-ng";
import axios from "axios";
import PlayIcon from "../Icons/PlayIcon.vue";
import UserPlusIcon from "../Icons/UserPlusIcon.vue";
import UserMinusIcon from "../Icons/UserMinusIcon.vue";
import { useToast } from "vue-toastification";

const toast = useToast();

// === Constants ===
const ROLE_HOST = "host";
const ROLE_AUDIENCE = "audience";
const CHANNEL_NAME = "auction";

// === Props ===
const props = defineProps({
    appId: String,
});

// === User & State ===
const page = usePage();
const authUser = page.props.auth.user;
const isActiveSeller = ref(page.props.is_active_seller);

const isHostLive = ref(false);
const isConnected = ref(false);
const isManualLeaving = ref(false);

// === Agora Setup ===
const client = AgoraRTC.createClient({ mode: "live", codec: "vp8" });
const localAudioTrack = ref(null);
const localVideoTrack = ref(null);

// === Event Handlers ===
const setupEventListeners = () => {
    client.on("user-published", handleUserPublished);
    client.on("user-unpublished", handleUserUnpublished);
    client.on("connection-state-change", handleConnectionChange);
};

const handleUserPublished = async (user, mediaType) => {
    console.log("User published:", user.uid);
    isHostLive.value = true;

    await client.subscribe(user, mediaType);
    if (mediaType === "video") user.videoTrack.play("remoteVideo");
    if (mediaType === "audio") user.audioTrack.play();
};

const handleUserUnpublished = (user, mediaType) => {
    console.log("User unpublished:", user.uid);
    isHostLive.value = false;
};

const handleConnectionChange = (curState, prevState) => {
    console.log(`Agora connection: ${prevState} → ${curState}`);

    if (authUser.is_active_seller) {
        if (isManualLeaving.value) {
            console.log(
                "Manual leave in progress. Bypassing auto-reconnect check."
            );
            return;
        }

        if (curState === "DISCONNECTED") {
            toast.warning("Connection lost. Reconnecting...");
            tryReconnectHost();
        }
    }
};

// === Token Fetcher ===
const fetchToken = async (channelName) => {
    const { data } = await axios.post("/agora/token", { channelName });
    return data.token;
};

// === Join Channel ===
const joinChannel = async (role) => {
    if (isConnected.value) return;

    const token = await fetchToken(CHANNEL_NAME);
    await client.join(props.appId, CHANNEL_NAME, token, authUser.id);
    isConnected.value = true;

    if (role === ROLE_HOST) {
        client.setClientRole(ROLE_HOST);
        [localAudioTrack.value, localVideoTrack.value] = await Promise.all([
            AgoraRTC.createMicrophoneAudioTrack(),
            AgoraRTC.createCameraVideoTrack(),
        ]);

        localVideoTrack.value.play("localVideo");
        await client.publish([localAudioTrack.value, localVideoTrack.value]);
        isHostLive.value = true;

        // Persist flag for accidental refresh/disconnection
        localStorage.setItem("hostRejoinNeeded", "true");
        localStorage.setItem("audienceRejoinNeeded", "true");

        toast.success("You are now live as host!");
    } else {
        client.setClientRole(ROLE_AUDIENCE, { level: 2 });

        // Handle late joiners: check if host is already live
        if (client.remoteUsers.length > 0) {
            console.log("Found host already streaming");
            isHostLive.value = true;
            for (const user of client.remoteUsers) {
                await client.subscribe(user, "video").catch(() => {});
                await client.subscribe(user, "audio").catch(() => {});
                if (user.videoTrack) user.videoTrack.play("remoteVideo");
                if (user.audioTrack) user.audioTrack.play();
            }
        } else {
            console.log("Waiting for host to start...");
        }
    }
};

// === Leave Channel ===
const leaveChannel = async (isUserInitiated = false) => {
    if (!isConnected.value) return;

    if (authUser.is_active_seller && isUserInitiated) {
        isManualLeaving.value = true;
    }

    if (localAudioTrack.value) localAudioTrack.value.close();
    if (localVideoTrack.value) localVideoTrack.value.close();

    localAudioTrack.value = null;
    localVideoTrack.value = null;

    await client.leave();
    isConnected.value = false;

    if (authUser.is_active_seller) {
        isHostLive.value = false;

        if (isUserInitiated) {
            // Clear flag from local storage
            localStorage.removeItem("hostRejoinNeeded");
            await nextTick();
            isManualLeaving.value = false;
            toast.info("You have ended your live stream.");

            return;
        }
    } else {
        // Redirect non-seller users
        router.get("/auction");
        return;
    }

    localStorage.removeItem("audienceRejoinNeeded");
};

// === Host Auto-Reconnect ===
const tryReconnectHost = async () => {
    if (authUser.is_active_seller) {
        await leaveChannel();
        await joinChannel(ROLE_HOST);
        toast.success("Reconnected successfully!");
    }
};

// === Prevent Accidental Refresh ===
const preventHostRefresh = (e) => {
    if (authUser.is_active_seller) {
        if (isConnected.value) {
            // Streaming: prevent page leave and tell the browser
            e.preventDefault();
            e.returnValue = "";
        } else {
            // Not streaming (e.g., manually left): allow normal leave and ensure flag is cleared
            localStorage.removeItem("hostRejoinNeeded");
        }
    }
};

// === Laravel Echo Integration (Optional) ===
const setupEchoListeners = () => {
    window.Echo.channel("sellers").listen(".SellerToggled", async (event) => {
        if (event.seller.user_id === authUser.id) {
            isActiveSeller.value = event.seller.is_active;

            if (!event.seller.is_active) {
                console.log(
                    "You were deactivated — ending your live stream..."
                );
                toast.info(
                    "You have been removed as the active seller. Ending stream..."
                );
                await leaveChannel(true);
            }
        } else {
            if (!event.seller.is_active) {
                console.log("Host has ended the stream — stopping playback...");
                toast.info("The host has ended the stream.");
                isHostLive.value = false;
            }
        }
    });

    window.Echo.channel("auction")
        .listen("HostJoined", () => {
            isHostLive.value = true;
        })
        .listen("HostLeft", () => {
            isHostLive.value = false;
        });
};

// === Lifecycle ===
// onMounted(() => {
//     setupEventListeners();
//     setupEchoListeners();

//     // Prevent accidental page refresh/leave
//     window.addEventListener("beforeunload", preventHostRefresh);

//     // Auto-rejoin only if host was live before (e.g., on page refresh or accidental disconnect)
//     if (
//         authUser.is_active_seller &&
//         localStorage.getItem("hostRejoinNeeded") === "true"
//     ) {
//         joinChannel(ROLE_HOST);
//     }

//     if (!authUser.is_active_seller) {
//         joinChannel(ROLE_AUDIENCE);
//     }
// });
onMounted(() => {
    setupEventListeners();
    setupEchoListeners();

    // Prevent accidental page refresh/leave
    window.addEventListener("beforeunload", preventHostRefresh);

    const rejoinNeeded = localStorage.getItem("hostRejoinNeeded") === "true";
    // const audienceRejoinNeeded =
    //     localStorage.getItem("audienceRejoinNeeded") === "true";

    // Auto-rejoin for host
    if (authUser.is_active_seller && rejoinNeeded) {
        joinChannel(ROLE_HOST);
    }
    // Auto-rejoin for non-active seller
    // if (!authUser.is_active_seller) {
    //     joinChannel(ROLE_AUDIENCE);
    // }
});

onBeforeUnmount(() => {
    window.removeEventListener("beforeunload", preventHostRefresh);

    // Pass true for hosts on unmount to clear the local storage flag
    // and prevent an immediate reconnect on the next visit.
    const isHost = authUser.is_active_seller;
    leaveChannel(isHost);
});
</script>

<template>
    <div class="flex flex-wrap gap-2 p-4 bg-gray-800">
        <template v-if="authUser.is_filament_admin || isActiveSeller">
            <button
                @click="joinChannel(ROLE_HOST)"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center"
            >
                <PlayIcon class="me-2 w-4 h-4" />
                Join as Host
            </button>
        </template>

        <button
            @click="joinChannel(ROLE_AUDIENCE)"
            class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg flex items-center"
        >
            <UserPlusIcon class="me-2 w-4 h-4" />
            Join as Audience
        </button>

        <template v-if="!audienceRejoinNeeded">
            <button
                @click="leaveChannel(true)"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg flex items-center"
            >
                <UserMinusIcon class="me-2 w-4 h-4" />
                Leave
            </button>
        </template>
    </div>

    <div class="relative bg-black aspect-video rounded-lg overflow-hidden">
        <transition name="fade">
            <div
                v-if="!isHostLive"
                class="absolute inset-0 flex flex-col items-center justify-center bg-black bg-opacity-80 text-gray-300"
            >
                <div class="animate-pulse text-xl font-semibold">
                    Waiting for host to start...
                </div>
                <div class="text-sm mt-2">
                    The auction will begin once the host goes live.
                </div>
            </div>
        </transition>

        <video
            v-show="authUser.is_active_seller && isHostLive"
            id="localVideo"
            class="w-full h-full object-cover"
            autoplay
            muted
            playsinline
        ></video>

        <video
            v-show="!authUser.is_active_seller && isHostLive"
            id="remoteVideo"
            class="w-full h-full object-cover"
            autoplay
            playsinline
        ></video>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
