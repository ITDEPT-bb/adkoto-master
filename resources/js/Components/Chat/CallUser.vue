<template>
    <main class="mt-12 overflow-y-auto scrollbar-thin">
        <div class="container mx-auto text-center">
            <h3 class="text-2xl font-bold">Voice Call</h3>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center p-8">
            <Spinner class="w-12 h-12 mx-auto text-blue-500" />
            <p class="mt-4 text-gray-600">Connecting...</p>
        </div>

        <div class="container mx-auto my-8" v-else>
            <!-- Call Controls -->
            <div class="flex justify-center">
                <div class="flex flex-wrap gap-4">
                    <button
                        type="button"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded flex items-center gap-2 disabled:opacity-50"
                        :disabled="callInProgress"
                        @click="initiateCall"
                    >
                        <phone-icon class="w-5 h-5" />
                        Call {{ user.name }}
                        <span
                            class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded"
                        >
                            {{ getUserStatus(user.id) }}
                        </span>
                    </button>
                </div>
            </div>

            <!-- Incoming Call -->
            <div class="my-8" v-if="incomingCall">
                <p class="text-lg">
                    Incoming Call From
                    <strong>{{ incomingCaller }}</strong>
                </p>
                <div class="flex justify-center gap-4 mt-4">
                    <button
                        type="button"
                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded"
                        @click="declineCall"
                    >
                        Decline
                    </button>
                    <button
                        type="button"
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded"
                        @click="acceptCall"
                    >
                        Accept
                    </button>
                </div>
            </div>

            <!-- Active Call -->
            <section
                v-if="callInProgress"
                class="w-full max-w-4xl mx-auto bg-white shadow-md rounded-lg overflow-hidden relative p-6 text-center"
            >
                <div class="text-xl font-semibold mb-4">
                    <user-group-icon class="w-8 h-8 inline-block mr-2" />
                    Voice Call with {{ user.name }}
                </div>

                <!-- Participant Indicators -->
                <div class="flex justify-center gap-4 mb-6">
                    <div class="flex items-center gap-2">
                        <microphone-icon class="w-6 h-6" />
                        <span>{{ mutedAudio ? "Muted" : "Speaking" }}</span>
                    </div>
                </div>

                <!-- Call Controls -->
                <div class="flex justify-center gap-4">
                    <button
                        type="button"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded flex items-center gap-2"
                        @click="toggleAudio"
                    >
                        <component
                            :is="
                                mutedAudio
                                    ? 'microphone-off-icon'
                                    : 'microphone-icon'
                            "
                            class="w-5 h-5"
                        />
                        {{ mutedAudio ? "Unmute" : "Mute" }}
                    </button>
                    <button
                        type="button"
                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded flex items-center gap-2"
                        @click="endCall"
                    >
                        <phone-off-icon class="w-5 h-5" />
                        End Call
                    </button>
                </div>
            </section>
        </div>
    </main>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import AgoraRTC from "agora-rtc-sdk-ng";
import axios from "axios";

import PhoneIcon from "../Icons/PhoneIcon.vue";
import MicrophoneIcon from "../Icons/MicIcon.vue";
import PhoneOffIcon from "../Icons/PhoneOffIcon.vue";
import UserGroupIcon from "../Icons/UserGroupIcon.vue";

import Spinner from "@/Components/Spinner.vue";

const props = defineProps({
    user: Object,
    agora_id: String,
});

const agora_id = "6e99ddb4c5e14ee79c0f4114936500dc";

// Reactive State
const authUser = usePage().props.auth.user;
const user = props.user;
const client = ref(null);
const localAudioTrack = ref(null);
const callInProgress = ref(false);
const loading = ref(false);
const errorMessage = ref(null);
const incomingCall = ref(false);
const incomingCaller = ref("");
const agoraChannel = ref(null);
const mutedAudio = ref(false);
const onlineUsers = reactive([]);
const ringtone = new Audio("/audio/ringtone_sound.mp3");
ringtone.loop = true;

// Constants
const MAX_RETRIES = 3;
const RETRY_DELAY = 1000;

// Lifecycle Hooks
onMounted(() => {
    initializePresenceChannel();
    initializeClient();
});

onUnmounted(async () => {
    await cleanupResources();
});

const initializeClient = () => {
    client.value = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
    setupAgoraClient();
};

// Agora Methods
const setupAgoraClient = () => {
    client.value.on("user-published", async (user, mediaType) => {
        try {
            await client.value.subscribe(user, mediaType);
            if (mediaType === "audio" && user.audioTrack) {
                user.audioTrack.play();
                console.log("User Published:", user);
            }
        } catch (error) {
            handleError("Error handling user-published:", error);
            console.error("not working");
        }
    });

    client.value.on("user-unpublished", (user) => {
        if (user.audioTrack) {
            user.audioTrack.stop();
        }
    });

    client.value.on("user-left", (user) => {
        client.value.remoteUsers = client.value.remoteUsers.filter(
            (u) => u.uid !== user.uid
        );
    });
};

const joinChannel = async (token, channelName, uid) => {
    loading.value = true;
    try {
        await client.value.join(agora_id, channelName, token, uid);
        await publishLocalAudio();
        callInProgress.value = true;
    } catch (error) {
        handleError("Failed to join channel:", error);
        throw error;
    } finally {
        loading.value = false;
    }
};

const publishLocalAudio = async () => {
    try {
        localAudioTrack.value = await AgoraRTC.createMicrophoneAudioTrack();
        await client.value.publish([localAudioTrack.value]);
    } catch (error) {
        handleError("Error creating audio track:", error);
        throw error;
    }
};

// Call Management
const initiateCall = async () => {
    if (!isUserOnline(user.id)) {
        showError(`${user.name} is offline`);
        return;
    }

    try {
        const channelName = generateChannelId(authUser.id, user.id);
        const token = await fetchTokenWithRetry(channelName);

        await axios.post("/agora/call-user", {
            user_to_call: user.id,
            channel_name: channelName,
        });

        await joinChannel(token, channelName, authUser.id);
    } catch (error) {
        handleError("Call initiation failed:", error);
    }
};

const acceptCall = async () => {
    try {
        const token = await fetchTokenWithRetry(agoraChannel.value);
        await joinChannel(token, agoraChannel.value, authUser.id);
        incomingCall.value = false;
        stopRingtone();
    } catch (error) {
        handleError("Error accepting call:", error);
    }
};

const endCall = async () => {
    try {
        await client.value.leave();
        await cleanupLocalTracks();
        callInProgress.value = false;
        await axios.post("/agora/end-call", { channel: agoraChannel.value });
    } catch (error) {
        handleError("Error ending call:", error);
    }
};

// Decline an incoming call
const declineCall = () => {
    incomingCall.value = false;
    agoraChannel.value = null;
    stopRingtone();
};

// Presence Channel
const initializePresenceChannel = () => {
    const userOnlineChannel = window.Echo.join("agora-online-channel");

    userOnlineChannel.here((users) => {
        onlineUsers.splice(0, onlineUsers.length, ...users);
    });

    userOnlineChannel.joining((user) => {
        if (!onlineUsers.find((data) => data.id === user.id)) {
            onlineUsers.push(user);
        }
    });

    userOnlineChannel.leaving((user) => {
        const index = onlineUsers.findIndex((data) => data.id === user.id);
        if (index > -1) onlineUsers.splice(index, 1);
    });

    userOnlineChannel.listen(".MakeAgoraCall", ({ data }) => {
        if (parseInt(data.userToCall) === parseInt(authUser.id)) {
            const caller = onlineUsers.find((user) => user.id === data.from);
            incomingCaller.value = caller ? caller.name : "Unknown Caller";
            agoraChannel.value = data.channelName;
            incomingCall.value = true;
            ringtone.play();
        }
    });
};

const stopRingtone = () => {
    ringtone.pause();
    ringtone.currentTime = 0;
};

// Helpers
const generateChannelId = (callerId, calleeId) =>
    `call-${Math.min(callerId, calleeId)}-${Math.max(callerId, calleeId)}`;

const fetchTokenWithRetry = async (channelName, attempt = 1) => {
    try {
        const response = await axios.post("/agora/token", { channelName });
        return response.data.token;
    } catch (error) {
        if (attempt <= MAX_RETRIES) {
            await new Promise((resolve) =>
                setTimeout(resolve, RETRY_DELAY * attempt)
            );
            return fetchTokenWithRetry(channelName, attempt + 1);
        }
        throw new Error("Token generation failed after multiple attempts");
    }
};

const cleanupLocalTracks = async () => {
    if (localAudioTrack.value) {
        localAudioTrack.value.stop();
        localAudioTrack.value.close();
        localAudioTrack.value = null;
    }
};

const cleanupResources = async () => {
    await endCall();
    window.Echo.leave("agora-online-channel");
};

const isUserOnline = (userId) => onlineUsers.some((user) => user.id === userId);

const handleError = (context, error) => {
    console.error(context, error);
    errorMessage.value = error.response?.data?.message || error.message;
};

const showError = (message) => {
    errorMessage.value = message;
    setTimeout(() => (errorMessage.value = null), 5000);
};

// UI Helpers
const getUserStatus = (userId) => (isUserOnline(userId) ? "Online" : "Offline");
</script>
