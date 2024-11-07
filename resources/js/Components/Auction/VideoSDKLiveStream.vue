<template>
	<div>
		<div id="textDiv">{{ message }}</div>
		<div class="controls">
			<button @click="joinAsSpeaker">Join as Speaker</button>
			<button @click="joinAsViewer">Join as Viewer</button>
			<button
				v-if="hlsControls"
				@click="startHLS">
				Start HLS
			</button>
			<button
				v-if="hlsControls"
				@click="stopHLS">
				Stop HLS
			</button>
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

const authUser = usePage().props.auth.user;

const message = ref("Join the meeting as a speaker or viewer");
const video = ref(null);
let meeting = null;
const hlsControls = ref(false);
const isVideoPlaying = ref(false);

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
		if (mode === "VIEWER" && status === "HLS_PLAYABLE") {
			playHLS(downstreamUrl);
		}
	});
};

// const playHLS = (url) => {
// 	isVideoPlaying.value = true;
// 	if (Hls.isSupported()) {
// 		const hls = new Hls();
// 		hls.loadSource(url);
// 		hls.attachMedia(video.value);
// 		hls.on(Hls.Events.MANIFEST_PARSED, () => {
// 			video.value.play().catch(console.error);
// 		});
// 	} else if (video.value.canPlayType("application/vnd.apple.mpegurl")) {
// 		video.value.src = url;
// 		video.value.addEventListener("loadedmetadata", () => {
// 			video.value.play().catch(console.error);
// 		});
// 	}
// };
const playHLS = (url) => {
	isVideoPlaying.value = true;
	if (Hls.isSupported()) {
		const hls = new Hls({
			maxLoadingDelay: 1, // max video loading delay used in automatic start level selection
			defaultAudioCodec: "mp4a.40.2", // default audio codec
			maxBufferLength: 0, // If buffer length is/become less than this value, a new fragment will be loaded
			maxMaxBufferLength: 1, // Hls.js will never exceed this value
			startLevel: 0, // Start playback at the lowest quality level
			startPosition: -1, // set -1 playback will start from intialtime = 0
			maxBufferHole: 0.001, // 'Maximum' inter-fragment buffer hole tolerance that hls.js can cope with when searching for the next fragment to load.
			highBufferWatchdogPeriod: 0, // if media element is expected to play and if currentTime has not moved for more than highBufferWatchdogPeriod and if there are more than maxBufferHole seconds buffered upfront, hls.js will jump buffer gaps, or try to nudge playhead to recover playback.
			nudgeOffset: 0.05, // In case playback continues to stall after first playhead nudging, currentTime will be nudged evenmore following nudgeOffset to try to restore playback. media.currentTime += (nb nudge retry -1)*nudgeOffset
			nudgeMaxRetry: 1, // Max nb of nudge retries before hls.js raise a fatal BUFFER_STALLED_ERROR
			maxFragLookUpTolerance: 0.1, // This tolerance factor is used during fragment lookup.
			liveSyncDurationCount: 1, // if set to 3, playback will start from fragment N-3, N being the last fragment of the live playlist
			abrEwmaFastLive: 1, // Fast bitrate Exponential moving average half-life, used to compute average bitrate for Live streams.
			abrEwmaSlowLive: 3, // Slow bitrate Exponential moving average half-life, used to compute average bitrate for Live streams.
			abrEwmaFastVoD: 1, // Fast bitrate Exponential moving average half-life, used to compute average bitrate for VoD streams
			abrEwmaSlowVoD: 3, // Slow bitrate Exponential moving average half-life, used to compute average bitrate for VoD streams
			maxStarvationDelay: 1,
		});
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
	isVideoPlaying.value = false; // Stop video display when HLS is stopped
};

const handleHLSStateChange = (state) => {
	console.log("HLS state changed:", state);
};

onUnmounted(() => {
	if (meeting) {
		meeting.leave();
	}
	video.value && (video.value.srcObject = null);
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
