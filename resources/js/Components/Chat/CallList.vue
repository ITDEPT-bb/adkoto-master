<template>
    <div>
        <!-- Top Bar -->
        <div
            class="flex flex-col sm:flex-row sm:items-center py-3 px-4 rounded-lg border-b-2 border-gray-200"
            style="background-color: #0076be"
        >
            <div class="flex items-center space-x-4">
                <Link
                    :href="route('chat.index')"
                    aria-label="Back to chat index"
                >
                    <button
                        class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6 text-gray-600"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </Link>
                <h2 class="text-white font-semibold text-lg">Start a Call</h2>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-h-[600px] overflow-y-auto space-y-4 px-4 py-6">
            <!-- User List -->
            <div
                v-for="user in users"
                :key="user.id"
                class="flex items-center justify-between p-3 bg-white border rounded-lg shadow-sm"
            >
                <!-- Left side: Avatar + Name/Status -->
                <div class="flex items-center space-x-4">
                    <img
                        :src="user.avatar_url"
                        alt="User avatar"
                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover"
                    />
                    <span
                        class="text-sm sm:text-base font-medium text-gray-800"
                    >
                        {{ user.name }} {{ user.surname }} -
                        {{ getUserStatus(user.id) }}
                    </span>
                </div>

                <!-- Right side: Call Button -->
                <button
                    class="px-4 py-2 text-sm sm:text-base font-semibold text-white bg-blue-500 rounded hover:bg-blue-600 transition"
                    @click="startCall(user)"
                    :disabled="callInProgress"
                >
                    {{ callInProgress ? "In Call" : "Call" }}
                </button>
            </div>

            <!-- Video Call View -->
            <div v-if="callInProgress" class="fixed inset-0 bg-black/90 z-50">
                <div class="flex flex-col h-full">
                    <!-- Timer Display -->
                    <div class="absolute top-4 left-4 text-white text-lg z-50">
                        Time remaining: {{ formattedTime }}
                    </div>

                    <!-- Remote Video -->
                    <div
                        class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 p-4"
                    >
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
                            <div
                                class="absolute bottom-2 left-2 text-white text-sm bg-black/50 px-2 py-1 rounded"
                            >
                                {{ remoteUser.name }}
                            </div>
                        </div>
                    </div>

                    <!-- Local Video -->
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

                    <!-- Controls -->
                    <div class="flex justify-center gap-4 p-4 bg-gray-800/50">
                        <button
                            @click="toggleAudio"
                            class="p-3 rounded-full bg-white/10 hover:bg-white/20 text-white transition"
                        >
                            <MicrophoneIcon
                                v-if="!mutedAudio"
                                class="w-6 h-6"
                            />
                            <SpeakerXMarkIcon v-else class="w-6 h-6" />
                        </button>
                        <button
                            @click="toggleVideo"
                            class="p-3 rounded-full bg-white/10 hover:bg-white/20 text-white"
                        >
                            <VideoCameraIcon
                                v-if="!disabledVideo"
                                class="w-6 h-6"
                            />
                            <VideoCameraSlashIcon v-else class="w-6 h-6" />
                        </button>
                        <button
                            @click="endCall"
                            class="p-3 rounded-full bg-red-500 hover:bg-red-600 text-white transition"
                        >
                            <PhoneXMarkIcon class="w-6 h-6" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Incoming Call -->
        <!-- <div class="my-8" v-if="incomingCall"> -->
        <div
            class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center"
            v-if="incomingCall"
        >
            <div class="text-center bg-white p-6 rounded shadow-md">
                <p class="text-lg text-dark">
                    Incoming Call From <strong>{{ incomingCaller }}</strong>
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

        <!-- Declined Call -->
        <div
            class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center"
            v-if="callStatus"
        >
            <div class="text-center bg-white p-6 rounded shadow-md">
                <p class="text-lg text-dark">{{ callStatusText }}</p>
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
    VideoCameraIcon,
    VideoCameraSlashIcon,
    PhoneXMarkIcon,
} from "@heroicons/vue/24/outline";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    users: Array,
    appId: String,
    authUser: Object,
});

const ringtone = new Audio("/audio/ringtone_sound.mp3");
const time_limit_sound = new Audio("/audio/call_time_limit_sound.wav");
ringtone.loop = true;
time_limit_sound.loop = false;

// Agora Client
const client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
AgoraRTC.setLogLevel(3);

// State
const localAudioTrack = ref(null);
const localVideoTrack = ref(null);
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
const incomingCallerId = ref("");

const callStatus = ref(false);
const callStatusText = ref("");

const otherUserId = ref(null);

// Timer-related variables
const callTimer = ref(null);
const callStartTime = ref(null);
const timeLeft = ref(25);

// Setup Agora
const setupAgora = async () => {
    client.on("user-published", async (user, mediaType) => {
        await client.subscribe(user, mediaType);

        if (mediaType === "video") {
            const remoteUser = {
                uid: user.uid,
                name: getUserName(user.uid),
                videoTrack: user.videoTrack,
                audioTrack: user.audioTrack,
            };

            remoteUsers.push(remoteUser);
            setTimeout(() => {
                user.videoTrack.play(`remoteVideo_${user.uid}`);
            }, 100);
        }

        if (mediaType === "audio") {
            user.audioTrack.play();
        }
    });

    client.on("user-unpublished", (user, mediaType) => {
        if (mediaType === "video") {
            const index = remoteUsers.findIndex((u) => u.uid === user.uid);
            if (index > -1) {
                remoteUsers.splice(index, 1);
            }
        }
    });
};
// const setupAgora = async () => {
//     client.on("user-published", async (user, mediaType) => {
//         try {
//             await client.subscribe(user, mediaType);

//             if (mediaType === "audio") {
//                 const remoteUser = {
//                     uid: user.uid,
//                     name: getUserName(user.uid),
//                     muted: false,
//                     audioTrack: user.audioTrack,
//                 };

//                 remoteUsers.push(remoteUser);
//                 user.audioTrack.play();

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

//         }
//     } catch (error) {
//         console.error("Error handling user-published:", error);
//     }
// });

// client.on("user-unpublished", (user, mediaType) => {
//     if (mediaType === "audio") {
//         const index = remoteUsers.findIndex((u) => u.uid === user.uid);
//         if (index > -1) remoteUsers.splice(index, 1);
//     }
// });

// client.on("user-left", (user) => {
//     const index = remoteUsers.findIndex((u) => u.uid === user.uid);
//     if (index > -1) remoteUsers.splice(index, 1);
// });
// };

const startCall = async (user) => {
    if (!isUserOnline(user.id)) {
        alert(`${user.name} is offline`);
        return;
    }

    try {
        otherUserId.value = user.id;

        channelName.value = generateChannelName(props.authUser.id, user.id);
        const token = await fetchToken(channelName.value);

        await client.join(
            props.appId,
            channelName.value,
            token,
            // props.authUser.id
            user.id
        );
        [localAudioTrack.value, localVideoTrack.value] = await Promise.all([
            AgoraRTC.createMicrophoneAudioTrack(),
            AgoraRTC.createCameraVideoTrack(),
        ]);

        await client.publish([localAudioTrack.value, localVideoTrack.value]);
        localVideoTrack.value.play("localVideo");

        // localAudioTrack.value = await AgoraRTC.createMicrophoneAudioTrack();
        // await client.publish([localAudioTrack.value]);
        callInProgress.value = true;

        // Start timer
        startCallTimer();

        // Notify user about the call
        await axios.post("/agora/call-user", {
            calleeId: user.id,
            callerId: props.authUser.id,
            channel: channelName.value,
            token: token,
            start_time: Date.now(),
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

        otherUserId.value = acceptedId.value;

        // Start timer
        startCallTimer();

        // Notify server about call acceptance
        await axios.post("/agora/accept-call", {
            channel: acceptedChannel.value,
            start_time: Date.now(),
        });

        await client.join(
            props.appId,
            acceptedChannel.value,
            acceptedToken.value,
            acceptedId.value
        );

        [localAudioTrack.value, localVideoTrack.value] = await Promise.all([
            AgoraRTC.createMicrophoneAudioTrack(),
            AgoraRTC.createCameraVideoTrack(),
        ]);

        await client.publish([localAudioTrack.value, localVideoTrack.value]);
        localVideoTrack.value.play("localVideo");
        // localAudioTrack.value = await AgoraRTC.createMicrophoneAudioTrack();
        // await client.publish([localAudioTrack.value]);
        callInProgress.value = true;

        ringtone.pause();
        incomingCall.value = false;
    } catch (error) {
        console.error("Error accepting call:", error);
    }
};

// const declineCall = () => {
//     incomingCall.value = false;
//     stopRingtone();
// };
const declineCall = async () => {
    incomingCall.value = false;
    stopRingtone();

    await axios.post("/agora/decline-call", {
        to_user_id: incomingCallerId.value,
    });
};

const removeCall = async () => {
    if (localAudioTrack.value) {
        localAudioTrack.value.stop();
        localAudioTrack.value.close();
        localAudioTrack.value = null;
    }

    if (localVideoTrack.value) {
        localVideoTrack.value.stop();
        localVideoTrack.value.close();
        localVideoTrack.value = null;
    }

    // Leave Agora session if already joined just in case
    try {
        await client.leave();
    } catch (error) {
        // Ignore error if not yet joined
    }

    // Clear remote users list
    remoteUsers.splice(0, remoteUsers.length);

    // Reset any call state
    callInProgress.value = false;
    acceptedId.value = null;
    acceptedChannel.value = null;
    acceptedToken.value = null;

    callStatus.value = true;
    callStatusText.value = "Call has been declined";
    setTimeout(() => {
        callStatus.value = false;
        callStatusText.value = "";
    }, 3000);
};

const endCall = async () => {
    // Clear timer
    if (callTimer.value) {
        clearInterval(callTimer.value);
        callTimer.value = null;
    }

    // Reset timer variables
    timeLeft.value = 15;
    callStartTime.value = null;

    if (localAudioTrack.value) {
        localAudioTrack.value.stop();
        localAudioTrack.value.close();
    }
    if (localVideoTrack.value) {
        localVideoTrack.value.stop();
        localVideoTrack.value.close();
    }

    await client.leave();
    remoteUsers.splice(0, remoteUsers.length);
    callInProgress.value = false;

    // await axios.post("/api/end-call", {
    //     channel: channelName.value,
    // });

    if (otherUserId.value) {
        await axios.post("/agora/end-call", {
            to_user_id: otherUserId.value,
        });
    }
};

// Timer management functions
const startCallTimer = () => {
    timeLeft.value = 25;
    callStartTime.value = Date.now();

    callTimer.value = setInterval(async () => {
        timeLeft.value =
            25 - Math.floor((Date.now() - callStartTime.value) / 1000);

        if (timeLeft.value <= 0) {
            await endCall();
            callStatusText.value = "Call time limit reached";
            callStatus.value = true;
            time_limit_sound.play();
            setTimeout(() => {
                callStatus.value = false;
            }, 3000);
        }
    }, 1000);
};

const endCallRemoved = () => {
    acceptedId.value = null;
    acceptedChannel.value = null;
    acceptedToken.value = null;

    callStatus.value = true;
    callStatusText.value = "Call has ended";
    setTimeout(() => {
        callStatus.value = false;
        callStatusText.value = "";
    }, 3000);
};

const toggleAudio = () => {
    mutedAudio.value = !mutedAudio.value;
    localAudioTrack.value.setEnabled(!mutedAudio.value);
};

const toggleVideo = () => {
    disabledVideo.value = !disabledVideo.value;
    localVideoTrack.value.setEnabled(!disabledVideo.value);
};

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
                incomingCallerId.value = data.from;
                acceptedId.value = data.from;
                acceptedChannel.value = data.channelName;
                acceptedToken.value = data.token;
            }
        });

    window.Echo.private(`calls.${props.authUser.id}`)
        .listen("DeclineCall", (e) => {
            console.log("Call declined:", e);
            removeCall();
        })
        .listen("CallEnded", (e) => {
            console.log("Call ended by:", e.fromUserId);
            endCallRemoved();
        })
        .listen("CallTimeEnded", async (e) => {
            if (
                e.channel === channelName.value ||
                e.channel === acceptedChannel.value
            ) {
                await endCall();
                callStatusText.value = "Call time limit reached";
                callStatus.value = true;
                setTimeout(() => {
                    callStatus.value = false;
                }, 3000);
            }
        });
});

onUnmounted(() => {
    endCall();
    window.Echo.leave("agora-online-channel");
});
</script>
