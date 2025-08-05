<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AgoraRTC from "agora-rtc-sdk-ng";
import axios from "axios";
import { onMounted, ref, defineProps } from "vue";

// Props
const props = defineProps({
    appId: String,
});

const authUser = usePage().props.auth.user;

// Agora client and tracks
const client = AgoraRTC.createClient({
    mode: "live",
    codec: "vp8",
    role: "host",
});
const channel = ref("");
const localAudioTrack = ref(null);
const localVideoTrack = ref(null);

// Listen for remote user publishing
const setupEventListeners = () => {
    client.on("user-published", async (user, mediaType) => {
        await client.subscribe(user, mediaType);
        console.log("subscribe success");
        if (mediaType === "video") {
            const remoteVideoTrack = user.videoTrack;
            remoteVideoTrack.play("remoteVideo");
        }
        if (mediaType === "audio") {
            user.audioTrack.play();
        }
    });

    client.on("user-unpublished", (user, mediaType) => {
        if (mediaType === "video" || mediaType === "audio") {
            console.log("Remote user unpublished:", user.uid);
        }
    });
};

// Create local tracks
const createLocalTracks = async () => {
    localAudioTrack.value = await AgoraRTC.createMicrophoneAudioTrack();
    localVideoTrack.value = await AgoraRTC.createCameraVideoTrack();
};

// Publish Local Tracks
const publishLocalTracks = async () => {
    await client.publish([localAudioTrack.value, localVideoTrack.value]);
};

const joinAsHost = async () => {
    channel.value = generateChannelName();
    const token = await fetchToken(channel.value);

    await client.join(props.appId, channel.value, token, authUser.id);

    // Play local video
    client.setClientRole("host");
    await createLocalTracks();
    localVideoTrack.value.play("localVideo");
    await publishLocalTracks();
};

const leaveChannel = async () => {
    if (localAudioTrack.value) {
        localAudioTrack.value.close();
        localAudioTrack.value = null;
    }
    if (localVideoTrack.value) {
        localVideoTrack.value.close();
        localVideoTrack.value = null;
    }
    await client.leave();
};

const generateChannelName = () => {
    return `channel-auction`;
};

const fetchToken = async (channelName) => {
    const response = await axios.post("/agora/token", { channelName });
    return response.data.token;
};

onMounted(() => {
    setupEventListeners();
});
</script>

<template>
    <Head title="Live Auction" />
    <AuthenticatedLayout>
        <div class="fixed inset-0 bg-black/95 flex items-center justify-center">
            <div
                class="flex flex-col justify-between w-full max-w-6xl mx-auto bg-white dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-gray-700 min-h-[300px] p-6 shadow-md"
            >
                <h1 class="text-white">Test Content HERE</h1>
                <div class="flex gap-3">
                    <button class="p-4 rounder bg-blue-400" @click="joinAsHost">
                        Join as Host
                    </button>
                    <button
                        class="p-4 rounder bg-blue-400"
                        @click="leaveChannel()"
                    >
                        Leave
                    </button>
                </div>

                <!-- Remote Video -->
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 p-4">
                    <div
                        class="relative bg-gray-800 rounded-lg overflow-hidden"
                    >
                        <video
                            id="remoteVideo"
                            class="w-full h-full object-cover"
                            autoplay
                            playsinline
                        ></video>
                    </div>
                </div>

                <!-- Local Video -->
                <div
                    class="fixed bottom-36 lg:bottom-4 right-4 w-48 h-32 rounded-lg overflow-hidden shadow-lg"
                >
                    <video
                        id="localVideo"
                        class="w-full h-full object-cover"
                        autoplay
                        muted
                        playsinline
                    ></video>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
