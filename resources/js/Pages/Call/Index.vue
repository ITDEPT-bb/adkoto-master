<template>
    <Head title="Call Page" />
    <AuthenticatedLayout>
        <div class="fixed inset-0 bg-black/95 flex items-center justify-center">
            <div
                class="flex flex-col justify-between w-full max-w-6xl mx-auto bg-white dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-gray-700 min-h-[300px] p-6 shadow-md"
            >
                <h1
                    class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-100"
                >
                    Call with {{ user.name }} {{ user.surname }}
                </h1>

                <div class="fixed inset-0 bg-black/90 z-50">
                    <div class="flex flex-col h-full">
                        <!-- Remote Video -->
                        <div
                            class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 p-4"
                        >
                            <div
                                class="relative bg-gray-800 rounded-lg overflow-hidden"
                            >
                                <video
                                    id="remoteVideo"
                                    class="w-full h-full object-cover"
                                    autoplay
                                    playsinline
                                ></video>
                                <div
                                    class="absolute bottom-2 left-2 text-white text-sm bg-black/50 px-2 py-1 rounded"
                                >
                                    {{ user.name }}
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
                                playsinline
                            ></video>
                        </div>

                        <!-- Controls -->
                        <div
                            class="flex justify-center gap-4 p-4 bg-gray-800/50"
                        >
                            <button
                                @click="toggleAudio"
                                class="p-3 rounded-full bg-white/10 hover:bg-white/20 text-white"
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
    </AuthenticatedLayout>
</template>

<script setup>
import { defineProps, onMounted, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/ChatLayout.vue";
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

// Props
const props = defineProps({
    user: Object,
    roomId: String,
    appId: String,
    isCaller: Boolean,
});

const authUser = usePage().props.auth.user;
const channelName = ref("");
const mutedAudio = ref(false);
const disabledVideo = ref(false);

const callStatus = ref(false);
const callStatusText = ref("");

// Agora client and tracks
const client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
const localAudioTrack = ref(null);
const localVideoTrack = ref(null);

// Join channel and setup tracks
const joinChannel = async () => {
    try {
        channelName.value = generateChannelName(authUser.id, props.user.id);
        const token = await fetchToken(channelName.value);

        await client.join(props.appId, channelName.value, token, authUser.id);

        // Create local tracks
        localAudioTrack.value = await AgoraRTC.createMicrophoneAudioTrack();
        localVideoTrack.value = await AgoraRTC.createCameraVideoTrack();

        // Play local video
        localVideoTrack.value.play("localVideo");

        // Publish local tracks
        await client.publish([localAudioTrack.value, localVideoTrack.value]);

        if (props.isCaller) {
            // Notify server to inform the callee
            await axios.post("/agora/call-user", {
                calleeId: props.user.id,
                callerId: authUser.id,
                channel: channelName.value,
                token,
                start_time: Date.now(),
            });
            console.log(
                "You are the caller, waiting for the callee to join..."
            );
        } else {
            console.log("You are the callee, call has been initiated.");
        }
    } catch (error) {
        console.error("Join channel error:", error);
    }
};

// Listen for remote user publishing
const setupEventListeners = () => {
    client.on("user-published", async (remoteUser, mediaType) => {
        await client.subscribe(remoteUser, mediaType);
        if (mediaType === "video") {
            const remoteVideoTrack = remoteUser.videoTrack;
            remoteVideoTrack.play("remoteVideo");
        }
        if (mediaType === "audio") {
            remoteUser.audioTrack.play();
        }
    });

    client.on("user-unpublished", (remoteUser, mediaType) => {
        if (mediaType === "video" || mediaType === "audio") {
            console.log("Remote user unpublished:", remoteUser.uid);
        }
    });
};

const fetchToken = async (channelName) => {
    const response = await axios.post("/agora/token", { channelName });
    return response.data.token;
};

const generateChannelName = (callerId, calleeId) => {
    return `call-${Math.min(callerId, calleeId)}-${Math.max(
        callerId,
        calleeId
    )}`;
};

const toggleAudio = () => {
    mutedAudio.value = !mutedAudio.value;
    localAudioTrack.value.setEnabled(!mutedAudio.value);
};

const toggleVideo = () => {
    disabledVideo.value = !disabledVideo.value;
    localVideoTrack.value.setEnabled(!disabledVideo.value);
};

const endCall = async () => {
    if (localAudioTrack.value) localAudioTrack.value.close();
    if (localVideoTrack.value) localVideoTrack.value.close();
    await client.leave();

    const userId = props.user.id;
    try {
        await axios.post("/agora/end-call", {
            to_user_id: userId,
        });

        callStatus.value = true;
        callStatusText.value = "Call has ended";
        setTimeout(() => {
            callStatus.value = false;
            callStatusText.value = "";
            window.location.href = "/chat";
        }, 3000);
    } catch (error) {
        console.error("Error ending call:", error);
    }
};

const removeCall = async () => {
    if (localAudioTrack.value) localAudioTrack.value.close();
    if (localVideoTrack.value) localVideoTrack.value.close();
    await client.leave();

    callStatus.value = true;
    callStatusText.value = "Call has been declined";
    setTimeout(() => {
        callStatus.value = false;
        callStatusText.value = "";
        window.location.href = "/chat";
    }, 3000);
};

onMounted(() => {
    setupEventListeners();
    joinChannel();

    window.Echo.private(`calls.${authUser.id}`)
        .listen("DeclineCall", () => {
            console.log("Call declined");
            removeCall();
        })
        .listen("CallEnded", (e) => {
            console.log("Call ended by user:", e.fromUserId);
            endCall();
        })
        .listen("CallTimeEnded", async (e) => {
            if (e.channel === channelName.value) {
                await endCall();
                alert("Call time limit reached");
            }
        });
});
</script>
