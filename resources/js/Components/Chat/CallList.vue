<template>
    <div class="space-y-2 px-10 py-4">
        <!-- User List -->
        <div
            v-for="user in users"
            :key="user.id"
            class="flex items-center justify-between p-2 border rounded-lg"
        >
            <span>{{ user.name }} - {{ getUserStatus(user.id) }}</span>
            <button
                class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
                @click="startCall(user)"
                :disabled="callInProgress"
            >
                {{ callInProgress ? "In Call" : "Call" }}
            </button>
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

        <!-- Video Container -->
        <div v-if="callInProgress" class="fixed inset-0 bg-black/90 z-50">
            <div class="flex flex-col h-full">
                <!-- Remote Video -->
                <div class="flex-1 grid grid-cols-1 gap-4 p-4">
                    <div
                        v-for="remoteUser in remoteUsers"
                        :key="remoteUser.uid"
                        class="relative bg-gray-800 rounded-lg overflow-hidden"
                    >
                        <video
                            :id="'remoteVideo_' + remoteUser.uid"
                            class="w-full h-full object-cover"
                            autoplay
                        ></video>
                        <div class="absolute bottom-2 left-2 text-white">
                            {{ remoteUser.name }}
                        </div>
                    </div>
                </div>

                <!-- Local Video Preview -->
                <div
                    class="fixed bottom-4 right-4 w-48 h-32 rounded-lg overflow-hidden shadow-lg"
                >
                    <video
                        id="localVideo"
                        class="w-full h-full object-cover"
                        autoplay
                        muted
                    ></video>
                </div>

                <!-- Call Controls -->
                <div class="flex justify-center gap-4 p-4 bg-gray-800/50">
                    <button
                        @click="toggleAudio"
                        class="p-3 rounded-full bg-white/10 hover:bg-white/20 text-white"
                    >
                        <MicrophoneIcon v-if="!mutedAudio" class="w-6 h-6" />
                        <SpeakerXMarkIcon v-else class="w-6 h-6" />
                    </button>
                    <!-- <button
                        @click="toggleVideo"
                        class="p-3 rounded-full bg-white/10 hover:bg-white/20 text-white"
                    >
                        <VideoCameraIcon
                            v-if="!disabledVideo"
                            class="w-6 h-6"
                        />
                        <VideoCameraSlashIcon v-else class="w-6 h-6" />
                    </button> -->
                    <button
                        @click="endCall"
                        class="p-3 rounded-full bg-red-500 hover:bg-red-600 text-white"
                    >
                        <PhoneXMarkIcon class="w-6 h-6" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted } from "vue";
import AgoraRTC from "agora-rtc-sdk-ng";
import axios from "axios";

// Icons
import {
    MicrophoneIcon,
    SpeakerXMarkIcon,
    // VideoCameraIcon,
    // VideoCameraSlashIcon,
    PhoneXMarkIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    users: Array,
    appId: String,
    authUser: Object,
});

const ringtone = new Audio("/audio/ringtone_sound.mp3");
ringtone.loop = true;

// Agora Client
const client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });

// State
const localAudioTrack = ref(null);
// const localVideoTrack = ref(null);
const callInProgress = ref(false);
const remoteUsers = reactive([]);
const mutedAudio = ref(false);
const disabledVideo = ref(false);
const onlineUsers = reactive([]);
const channelName = ref("");

const acceptedId = ref(null);
const acceptedChannel = ref("");
const acceptedToken = ref("");

const incomingCall = ref(false);
const incomingCaller = ref("");

// Setup Agora
// const setupAgora = async () => {
//     client.on("user-published", async (user, mediaType) => {
//         await client.subscribe(user, mediaType);

//         if (mediaType === "video") {
//             const remoteUser = {
//                 uid: user.uid,
//                 name: getUserName(user.uid),
//                 videoTrack: user.videoTrack,
//                 audioTrack: user.audioTrack,
//             };

//             remoteUsers.push(remoteUser);
//             setTimeout(() => {
//                 user.videoTrack.play(`remoteVideo_${user.uid}`);
//             }, 100);
//         }

//         if (mediaType === "audio") {
//             user.audioTrack.play();
//         }
//     });

//     client.on("user-unpublished", (user, mediaType) => {
//         if (mediaType === "video") {
//             const index = remoteUsers.findIndex((u) => u.uid === user.uid);
//             if (index > -1) {
//                 remoteUsers.splice(index, 1);
//             }
//         }
//     });
// };
const setupAgora = async () => {
    client.on("user-published", async (user, mediaType) => {
        try {
            await client.subscribe(user, mediaType);

            if (mediaType === "audio") {
                const remoteUser = {
                    uid: user.uid,
                    name: getUserName(user.uid),
                    muted: false,
                    audioTrack: user.audioTrack,
                };

                remoteUsers.push(remoteUser);
                user.audioTrack.play();

                // Track audio state changes
                // user.audioTrack.on("track-ended", () => {
                //     const index = remoteUsers.findIndex(
                //         (u) => u.uid === user.uid
                //     );
                //     if (index > -1) remoteUsers[index].muted = true;
                // });

                // user.audioTrack.on("track-playing", () => {
                //     const index = remoteUsers.findIndex(
                //         (u) => u.uid === user.uid
                //     );
                //     if (index > -1) remoteUsers[index].muted = false;
                // });
            }
        } catch (error) {
            console.error("Error handling user-published:", error);
        }
    });

    client.on("user-unpublished", (user, mediaType) => {
        if (mediaType === "audio") {
            const index = remoteUsers.findIndex((u) => u.uid === user.uid);
            if (index > -1) remoteUsers.splice(index, 1);
        }
    });

    // client.on("user-left", (user) => {
    //     const index = remoteUsers.findIndex((u) => u.uid === user.uid);
    //     if (index > -1) remoteUsers.splice(index, 1);
    // });
};

const startCall = async (user) => {
    if (!isUserOnline(user.id)) {
        alert(`${user.name} is offline`);
        return;
    }

    try {
        channelName.value = generateChannelName(props.authUser.id, user.id);
        const token = await fetchToken(channelName.value);

        await client.join(
            props.appId,
            channelName.value,
            token,
            // props.authUser.id
            user.id
        );
        // [localAudioTrack.value, localVideoTrack.value] = await Promise.all([
        //     AgoraRTC.createMicrophoneAudioTrack(),
        //     AgoraRTC.createCameraVideoTrack(),
        // ]);

        // await client.publish([localAudioTrack.value, localVideoTrack.value]);
        // localVideoTrack.value.play("localVideo");

        localAudioTrack.value = await AgoraRTC.createMicrophoneAudioTrack();
        await client.publish([localAudioTrack.value]);
        callInProgress.value = true;

        // Notify user about the call
        await axios.post("/agora/call-user", {
            calleeId: user.id,
            callerId: props.authUser.id,
            channel: channelName.value,
            token: token,
        });
    } catch (error) {
        console.error("Call failed:", error);
        endCall();
    }
};

const acceptCall = async () => {
    // if (!accepterId.value) return;

    try {
        // channelName.value = generateChannelName(
        //     props.authUser.id,
        //     accepterId.value
        // );
        // const token = await fetchToken(channelName.value);

        await client.join(
            props.appId,
            acceptedChannel.value,
            acceptedToken.value,
            acceptedId.value
        );

        localAudioTrack.value = await AgoraRTC.createMicrophoneAudioTrack();
        await client.publish([localAudioTrack.value]);
        callInProgress.value = true;

        ringtone.pause();
        incomingCall.value = false;
    } catch (error) {
        console.error("Error accepting call:", error);
    }
};

const declineCall = () => {
    incomingCall.value = false;
    stopRingtone();
};

const endCall = async () => {
    if (localAudioTrack.value) {
        localAudioTrack.value.stop();
        localAudioTrack.value.close();
    }
    // if (localVideoTrack.value) {
    //     localVideoTrack.value.stop();
    //     localVideoTrack.value.close();
    // }

    await client.leave();
    remoteUsers.splice(0, remoteUsers.length);
    callInProgress.value = false;

    // await axios.post("/api/end-call", {
    //     channel: channelName.value,
    // });
};

const toggleAudio = () => {
    mutedAudio.value = !mutedAudio.value;
    localAudioTrack.value.setEnabled(!mutedAudio.value);
};

// const toggleVideo = () => {
//     disabledVideo.value = !disabledVideo.value;
//     localVideoTrack.value.setEnabled(!disabledVideo.value);
// };

const generateChannelName = (callerId, calleeId) => {
    return `call-${Math.min(callerId, calleeId)}-${Math.max(
        callerId,
        calleeId
    )}`;
};

const fetchToken = async (channelName) => {
    const response = await axios.post("/agora/token", { channelName });
    return response.data.token;
};

const isUserOnline = (userId) => onlineUsers.some((u) => u.id === userId);
const getUserStatus = (userId) => (isUserOnline(userId) ? "Online" : "Offline");
const getUserName = (uid) =>
    props.users.find((u) => u.id === uid)?.name || "Unknown";

const stopRingtone = () => {
    ringtone.pause();
    ringtone.currentTime = 0;
};

// Presence Channel Setup
onMounted(() => {
    setupAgora();
    window.Echo.join("agora-online-channel")
        .here((users) => onlineUsers.push(...users))
        .joining((user) => onlineUsers.push(user))
        .leaving((user) => {
            const index = onlineUsers.findIndex((u) => u.id === user.id);
            if (index > -1) onlineUsers.splice(index, 1);
        })
        .listen(".MakeAgoraCall", ({ data }) => {
            if (parseInt(data.userToCall) === parseInt(props.authUser.id)) {
                console.log("Incoming call from", data.from);
                const caller = onlineUsers.find(
                    (user) => user.id === data.from
                );
                incomingCaller.value = caller ? caller.name : "Unknown Caller";
                incomingCall.value = true;
                ringtone.play();

                // Set the channel name and token for accepting the call
                acceptedId.value = data.from;
                acceptedChannel.value = data.channelName;
                acceptedToken.value = data.token;
            }
        });
});

onUnmounted(() => {
    endCall();
    window.Echo.leave("agora-online-channel");
});
</script>
