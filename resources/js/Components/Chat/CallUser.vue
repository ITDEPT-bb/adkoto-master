<template>
    <main class="mt-12 overflow-y-auto scrollbar-thin">
        <div class="container mx-auto text-center">
            <h3 class="text-2xl font-bold">Voice Call</h3>
        </div>
        <div class="container mx-auto my-8">
            <div class="flex justify-center">
                <div class="flex flex-wrap gap-4">
                    <button
                        type="button"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded flex items-center gap-2"
                        @click="placeCall(authUser.id, user.name, user.id)"
                    >
                        Call {{ user.name }}
                        <span
                            class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded"
                        >
                            {{ getUserOnlineStatus(user.id) }}
                        </span>
                    </button>
                </div>
            </div>

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
        </div>

        <section
            v-if="callPlaced"
            class="w-full max-w-4xl mx-auto bg-white shadow-md rounded-lg overflow-hidden relative p-6 text-center"
        >
            <div class="text-xl font-semibold mb-4">
                Voice Call in Progress...
            </div>
            <div class="flex justify-center gap-4">
                <button
                    type="button"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded"
                    @click="toggleAudio"
                >
                    {{ mutedAudio ? "Unmute" : "Mute" }}
                </button>
                <button
                    type="button"
                    class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded"
                    @click="endCall"
                >
                    End Call
                </button>
            </div>
        </section>
    </main>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import AgoraRTC from "agora-rtc-sdk-ng";

const props = defineProps({
    user: Object,
    agora_id: String,
});

const user = props.user;
const authUser = usePage().props.auth.user;

const callPlaced = ref(false);
const client = ref(null);
const localAudioTrack = ref(null);
const mutedAudio = ref(false);
const userOnlineChannel = ref(null);
const incomingCall = ref(false);
const incomingCaller = ref("");
const agoraChannel = ref(null);
const onlineUsers = reactive([]);

// Initialize AgoraRTC client
const initializeClient = () => {
    if (!client.value) {
        client.value = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
        setupEventListeners();
    }
};

// Set up event listeners for AgoraRTC
const setupEventListeners = () => {
    client.value.on("user-published", async (user, mediaType) => {
        try {
            // Use Agora's built-in subscription mechanism
            // await client.value.subscribe(user, mediaType);

            // Add additional verification after subscription
            if (mediaType === "audio" && user.audioTrack) {
                user.audioTrack.play();
            }
        } catch (error) {
            console.error("Error handling user-published:", error);
        }
    });

    client.value.on("user-unpublished", (user) => {
        if (user.audioTrack) {
            user.audioTrack.stop();
            user.audioTrack = null;
        }
    });

    client.value.on("user-left", (user) => {
        if (user.audioTrack) {
            user.audioTrack.stop();
            user.audioTrack = null;
        }
        // Remove user from remoteUsers list
        client.value.remoteUsers = client.value.remoteUsers.filter(
            (u) => u.uid !== user.uid
        );
    });
};

// Create a local audio track
const createLocalAudioTrack = async () => {
    localAudioTrack.value = await AgoraRTC.createMicrophoneAudioTrack();
};

// Join the channel and publish local audio
const joinChannel = async (token, channelName) => {
    try {
        if (client.value) {
            await client.value.leave();
            client.value = null;
        }
        initializeClient();
        await client.value.join(props.agora_id, channelName, token, null);
        await createLocalAudioTrack();
        await client.value.publish([localAudioTrack.value]);
        callPlaced.value = true;
    } catch (error) {
        console.error("Failed to join channel:", error);
        throw error;
    }
};

// Leave the channel and clean up
const leaveChannel = async () => {
    try {
        if (localAudioTrack.value) {
            localAudioTrack.value.close();
            localAudioTrack.value = null;
        }
        if (client.value) {
            await client.value.leave();
            client.value = null;
            callPlaced.value = false;
        }
    } catch (error) {
        console.error("Error leaving channel:", error);
    }
};

// Toggle audio mute/unmute
const toggleAudio = () => {
    if (localAudioTrack.value) {
        mutedAudio.value = !mutedAudio.value;
        localAudioTrack.value.setMuted(mutedAudio.value);
    }
};

// End the call
const endCall = async () => {
    await leaveChannel();
};

// Initialize user online status channel
const initUserOnlineChannel = () => {
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
        }
    });
};

// Get user online status
const getUserOnlineStatus = (id) => {
    return onlineUsers.find((user) => user.id === id) ? "Online" : "Offline";
};

// Place a call
const placeCall = async (id, calleeName, calleeId) => {
    try {
        const channelName = `${id}_${calleeName}`;
        const token = await generateToken(channelName);

        await axios.post("/agora/call-user", {
            user_to_call: calleeId,
            username: authUser.username,
            channel_name: channelName,
        });

        initializeClient();
        await joinChannel(token, channelName);
    } catch (error) {
        console.error(
            "Error in placeCall:",
            error.response?.data || error.message
        );
    }
};

// Accept an incoming call
const acceptCall = async () => {
    try {
        const token = await generateToken(agoraChannel.value);
        initializeClient();
        incomingCall.value = false;
        await joinChannel(token, agoraChannel.value);
    } catch (error) {
        console.error("Error in acceptCall:", error);
        incomingCall.value = false;
    }
};

// Decline an incoming call
const declineCall = () => {
    incomingCall.value = false;
    agoraChannel.value = null;
};

// Generate a token for the channel
const generateToken = async (channelName) => {
    try {
        const response = await axios.post("/agora/token", { channelName });
        return response.data.token;
    } catch (error) {
        alert("Failed to generate token. Please try again.");
        throw error;
    }
};

// Initialize on component mount
onMounted(() => {
    initUserOnlineChannel();
    initializeClient();
});
</script>
