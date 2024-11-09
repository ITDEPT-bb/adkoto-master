<template>
	<div>
		<div id="textDiv">{{ message }}</div>
		<div class="controls">
			<PrimaryButton
				v-if="authUser.is_filament_admin"
				@click="joinAsSpeaker">
				Join as Speaker
			</PrimaryButton>
			<PrimaryButton
				v-if="!isStreamLive"
				@click="joinAsViewer">
				Join as Viewer
			</PrimaryButton>
			<PrimaryButton
				v-if="hlsControls"
				@click="startHLS">
				Start HLS
			</PrimaryButton>
			<PrimaryButton
				v-if="hlsControls"
				@click="stopHLS">
				Stop HLS
			</PrimaryButton>
			<!-- New button for streamer to end meeting -->
			<PrimaryButton
				v-if="authUser.is_filament_admin && hlsControls"
				@click="endMeeting">
				End Meeting
			</PrimaryButton>
			<!-- New button for viewer to leave stream -->
			<PrimaryButton
				v-if="!authUser.is_filament_admin && isStreamLive"
				@click="leaveStream">
				Leave Stream
			</PrimaryButton>
		</div>
		<div id="videoContainer">
			<video
				ref="video"
				controls
				autoplay
				v-show="isVideoPlaying"></video>
		</div>
	</div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { VideoSDK } from "@videosdk.live/js-sdk";
import Hls from "hls.js";
import { usePage } from "@inertiajs/vue3";

import PrimaryButton from "@/Components/PrimaryButton.vue";

const authUser = usePage().props.auth.user;

const message = ref("Join the meeting as a speaker or viewer");
const video = ref(null);
let meeting = null;
const hlsControls = ref(false);
const isVideoPlaying = ref(false);
const isStreamLive = ref(false);

const joinAsSpeaker = async () => {
	message.value = "Joining as Speaker...";
	await initializeMeeting("CONFERENCE");
};

const joinAsViewer = async () => {
	message.value = "Joining as Viewer...";
	await initializeMeeting("VIEWER");
};

const initializeMeeting = async (mode) => {
	VideoSDK.config(
		"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcGlrZXkiOiJhNDFmZGU3Zi04ODk2LTRjOTMtOTYwNC1mZDcwZmIzZjgwNTYiLCJwZXJtaXNzaW9ucyI6WyJhbGxvd19qb2luIl0sImlhdCI6MTczMDk2MzgzOCwiZXhwIjoxODg4NzUxODM4fQ.aJo8lxaAzan238nzcXfxIVsWBEABPoHr9hn5a4XRLmk"
	);

	meeting = VideoSDK.initMeeting({
		meetingId: "dhnh-0p28-elwr",
		name: "Auction Host",
		micEnabled: true,
		webcamEnabled: mode === "CONFERENCE",
		mode,
	});

	meeting.join();
	setEventHandlers(mode);
};

const setEventHandlers = (mode) => {
	meeting.on("meeting-joined", () => {
		message.value = `Meeting joined as ${mode === "CONFERENCE" ? "Speaker" : "Viewer"}`;
		if (mode === "CONFERENCE") createLocalParticipant();
		hlsControls.value = mode === "CONFERENCE";
	});

	meeting.on("hls-state-changed", ({ downstreamUrl, status }) => {
		handleHLSStateChange(status);
		isStreamLive.value = status === "HLS_PLAYABLE";
		if (mode === "VIEWER" && status === "HLS_PLAYABLE") {
			playHLS(downstreamUrl);
		}
	});

	meeting.on("meeting-ended", () => {
		endStreamForAll();
	});
};

// Polling function to check stream status
const pollStreamStatus = () => {
	const interval = setInterval(async () => {
		const status = await meeting.getHlsState();
		isStreamLive.value = status === "HLS_PLAYABLE";
		if (!isStreamLive.value) clearInterval(interval);
	}, 5000);
};

onMounted(() => {
	pollStreamStatus();
});

const playHLS = (url) => {
	isVideoPlaying.value = true;
	if (Hls.isSupported()) {
		const hls = new Hls();
		hls.loadSource(url);
		hls.attachMedia(video.value);
		hls.on(Hls.Events.MANIFEST_PARSED, () => {
			video.value.play().catch(console.error);
		});
	} else if (video.value.canPlayType("application/vnd.apple.mpegurl")) {
		video.value.src = url;
		video.value.addEventListener("loadedmetadata", () => {
			video.value.play().catch(console.error);
		});
	}
};

const createLocalParticipant = () => {
	const videoElement = document.createElement("video");
	videoElement.classList.add("video-frame");
	videoElement.autoplay = true;
	videoElement.playsInline = true;
	document.getElementById("videoContainer").appendChild(videoElement);

	meeting.localParticipant.on("stream-enabled", (stream) => setTrack(stream, videoElement));
};

const setTrack = (stream, element) => {
	const mediaStream = new MediaStream();
	mediaStream.addTrack(stream.track);
	element.srcObject = mediaStream;
	element.play().catch(console.error);
};

const startHLS = () => {
	meeting.startHls({
		layout: { type: "SPOTLIGHT", priority: "PIN", gridSize: 9 },
		theme: "DEFAULT",
	});
};

const stopHLS = () => {
	meeting.stopHls();
	isVideoPlaying.value = false;
};

const handleHLSStateChange = (state) => {
	console.log("HLS state changed:", state);
};

// Function to end the meeting and notify viewers
const endMeeting = () => {
	meeting.end();
	endStreamForAll();
};

// Helper function to end the stream for all participants
const endStreamForAll = () => {
	hlsControls.value = false;
	isVideoPlaying.value = false;
	isStreamLive.value = false;
	message.value = "Meeting has ended.";
	if (video.value) video.value.srcObject = null;
};

// New function to leave the stream for viewers
const leaveStream = () => {
	meeting.leave();
	isVideoPlaying.value = false;
	isStreamLive.value = false;
	message.value = "You have left the stream.";
	if (video.value) video.value.srcObject = null;
};

onUnmounted(() => {
	if (meeting) {
		meeting.leave();
	}
	if (video.value) {
		video.value.srcObject = null;
	}
});
</script>

<style scoped>
#videoContainer {
	margin-top: 20px;
}
.controls {
	display: flex;
	gap: 10px;
}
.video-frame {
	width: 100%;
	height: auto;
}
</style>
