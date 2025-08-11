<template>
    <div
        class="bg-black rounded h-64 flex items-center justify-center text-white"
    >
        <div class="flex flex-wrap gap-2 p-4 bg-gray-800">
            <div class="flex items-center gap-4">
                <span class="text-xs font-semibold flex items-center">
                    <span
                        :class="{
                            'text-green-500': isConnected,
                            'text-red-500': !isConnected,
                        }"
                        >●</span
                    >
                    {{ isConnected ? "Live" : "Disconnected" }}
                </span>
            </div>
            <template v-if="authUser.is_filament_admin">
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

            <button
                @click="leaveChannel"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg flex items-center"
            >
                <UserMinusIcon class="me-2 w-4 h-4" />
                Leave
            </button>
        </div>
        <video
            :id="authUser.is_filament_admin ? 'localVideo' : 'remoteVideo'"
            class="w-full h-full object-cover"
            autoplay
            :muted="authUser.is_filament_admin"
            playsinline
        ></video>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import AgoraRTC from "agora-rtc-sdk-ng";

const props = defineProps({ appId: String, authUser: Object });
const emit = defineEmits(["joined", "left"]);

const authUser = props.authUser;

// Constants
const ROLE_HOST = "host";
const ROLE_AUDIENCE = "audience";
const CHANNEL_NAME = "auction";

const isHost = ref(false);
// Agora refs
const client = AgoraRTC.createClient({ mode: "live", codec: "vp8" });
const localAudioTrack = ref(null);
const localVideoTrack = ref(null);

onMounted(() => {
    setupEventListeners();
});

// Agora methods
const setupEventListeners = () => {
    client.on("user-published", handleUserPublished);
    client.on("user-unpublished", handleUserUnpublished);
};

const handleUserPublished = async (user, mediaType) => {
    await client.subscribe(user, mediaType);
    if (mediaType === "video") user.videoTrack.play("remoteVideo");
    if (mediaType === "audio") user.audioTrack.play();
};

const handleUserUnpublished = (user, mediaType) => {
    if (["video", "audio"].includes(mediaType)) {
        console.log("Remote user unpublished:", user.uid);
    }
};

const createLocalTracks = async () => {
    [localAudioTrack.value, localVideoTrack.value] = await Promise.all([
        AgoraRTC.createMicrophoneAudioTrack(),
        AgoraRTC.createCameraVideoTrack(),
    ]);
};

const publishLocalTracks = async () => {
    if (localAudioTrack.value && localVideoTrack.value) {
        await client.publish([localAudioTrack.value, localVideoTrack.value]);
    }
};

const joinChannel = async (role) => {
    const token = await fetchToken(CHANNEL_NAME);
    await client.join(props.appId, CHANNEL_NAME, token, authUser.id);

    if (role === ROLE_HOST) {
        client.setClientRole(ROLE_HOST);
        await createLocalTracks();
        localVideoTrack.value.play("localVideo");
        await publishLocalTracks();
        emit("joined");
    } else {
        client.setClientRole(ROLE_AUDIENCE, { level: 2 });
    }
};

const leaveChannel = async () => {
    if (localAudioTrack.value) localAudioTrack.value.close();
    if (localVideoTrack.value) localVideoTrack.value.close();

    localAudioTrack.value = null;
    localVideoTrack.value = null;

    await client.leave();
    stopTimer();
    emit("left");
};

const fetchToken = async (channelName) => {
    const response = await axios.post("/agora/token", { channelName });
    return response.data.token;
};
</script>
