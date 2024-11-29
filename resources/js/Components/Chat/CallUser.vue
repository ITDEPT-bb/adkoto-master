<template>
	<main class="mt-12 overflow-y-auto">
		<div class="container mx-auto text-center">
			<h3 class="text-2xl font-bold">Video Call</h3>
		</div>
		<div class="container mx-auto my-8">
			<div class="flex justify-center">
				<div class="flex flex-wrap gap-4">
					<button
						type="button"
						class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded flex items-center gap-2"
						@click="placeCall(authUser.id, user.name, user.id)">
						Call {{ user.name }}
						<span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">
							{{ getUserOnlineStatus(user.id) }}
						</span>
					</button>
				</div>
			</div>

			<!-- Incoming Call -->
			<div
				class="my-8"
				v-if="incomingCall">
				<p class="text-lg">
					Incoming Call From
					<strong>{{ incomingCaller }}</strong>
				</p>
				<div class="flex justify-center gap-4 mt-4">
					<button
						type="button"
						class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded"
						@click="declineCall">
						Decline
					</button>
					<button
						type="button"
						class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded"
						@click="acceptCall">
						Accept
					</button>
				</div>
			</div>
			<!-- End of Incoming Call -->
		</div>

		<section
			id="video-container"
			v-if="callPlaced"
			class="w-full max-w-4xl mx-auto bg-white shadow-md rounded-lg overflow-hidden relative">
			<div
				id="local-video"
				class="w-32 h-32 absolute bottom-4 left-4 border border-white rounded-lg z-10 bg-gray-900"></div>
			<div
				id="remote-video"
				class="w-full h-[500px] bg-black z-0"></div>
			<div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex gap-4 z-20">
				<button
					type="button"
					class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded"
					@click="toggleAudio">
					{{ mutedAudio ? "Unmute" : "Mute" }}
				</button>
				<button
					type="button"
					class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded"
					@click="toggleVideo">
					{{ mutedVideo ? "Show Video" : "Hide Video" }}
				</button>
				<button
					type="button"
					class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded"
					@click="endCall">
					End Call
				</button>
			</div>
		</section>
	</main>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
// import axios from "axios";

// Props from parent
const props = defineProps({
	user: Object,
	agora_id: String,
});

const user = props.user;
const authUser = usePage().props.auth.user;

const callPlaced = ref(false);
const client = ref(null);
const localStream = ref(null);
const mutedAudio = ref(false);
const mutedVideo = ref(false);
const userOnlineChannel = ref(null);
const incomingCall = ref(false);
const incomingCaller = ref("");
const agoraChannel = ref(null);
const onlineUsers = reactive([]);

const initUserOnlineChannel = () => {
	const userOnlineChannel = window.Echo.join("agora-online-channel");

	userOnlineChannel.here((users) => {
		console.log("Online users:", users);
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
		console.log("Incoming call data:", data);

		if (parseInt(data.userToCall) === parseInt(authUser.id)) {
			const caller = onlineUsers.find((user) => user.id === data.from);
			incomingCaller.value = caller ? caller.name : "Unknown Caller";
			agoraChannel.value = data.channelName;
			incomingCall.value = true;
		}
	});
};

const getUserOnlineStatus = (id) => {
	return onlineUsers.find((user) => user.id === id) ? "Online" : "Offline";
};

const placeCall = async (id, calleeName, calleeId) => {
	try {
		const channelName = `${id}_${calleeName}`;
		const tokenRes = await generateToken(channelName);

		await axios.post("/agora/call-user", {
			user_to_call: calleeId,
			username: authUser.username,
			channel_name: channelName,
		});

		initializeAgora(tokenRes.data.token, channelName);
	} catch (error) {
		console.error("Error in placeCall:", error.response?.data || error.message);
	}
};

const acceptCall = async () => {
	try {
		const tokenRes = await generateToken(agoraChannel.value);

		console.log("Token received for acceptCall:", tokenRes.data.token);

		initializeAgora(tokenRes.data.token, agoraChannel.value);

		incomingCall.value = false;
		callPlaced.value = true;
	} catch (error) {
		console.error("Error in acceptCall:", error.response?.data || error.message);
	}
};

const declineCall = () => {
	incomingCall.value = false;
};

const generateToken = (channelName) => {
	return axios
		.post("/agora/token", { channelName })
		.then((response) => {
			if (!response.data.token) {
				console.error("No token received:", response);
				throw new Error("Token generation failed");
			}
			console.log("Token received:", response.data.token);
			return response;
		})
		.catch((error) => {
			console.error("Error generating token:", error.response?.data || error.message);
			alert("Failed to generate token. Please try again.");
		});
};

const initializeAgora = (token, channelName) => {
	client.value = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
	const uid = authUser.id;

	client.value
		.join(props.agora_id, channelName, token, uid)
		.then((uid) => {
			console.log("Joined channel with UID:", uid);
			callPlaced.value = true;
			createLocalStream();

			client.value.on("user-published", async (user, mediaType) => {
				try {
					// Log when the user publishes
					console.log("User published:", user);

					// Make sure the user is actually publishing a stream
					if (user && user.videoTrack) {
						console.log("Subscribing to video track...");
						await client.value.subscribe(user, mediaType);
						if (mediaType === "video") {
							console.log("Playing video...");
							user.videoTrack.play("remote-video");
						}
					} else if (user && user.audioTrack) {
						console.log("Subscribing to audio track...");
						await client.value.subscribe(user, mediaType);
						if (mediaType === "audio") {
							user.audioTrack.play();
						}
					} else {
						console.warn("User published with no media (audio/video).");
					}
				} catch (error) {
					console.error("Failed to subscribe to remote stream:", error);
				}
			});

			// Listen for user-left events to handle disconnection
			client.value.on("user-left", (user) => {
				console.log("User left:", user);
				// Optionally, stop the video/audio track if needed
				user.videoTrack.stop();
				user.audioTrack.stop();
			});
		})
		.catch((err) => {
			console.error("Failed to join channel:", err);
		});
};

const joinRoom = (token, channel) => {
	client.value.join(
		token,
		channel,
		props.authuser,
		(uid) => {
			console.log(`User ${uid} joined channel`);
			callPlaced.value = true;
			createLocalStream();
			client.value.on("stream-added", (evt) => {
				const remoteStream = evt.stream;
				client.value.subscribe(remoteStream, (err) =>
					console.error("Subscribe stream failed", err)
				);
			});

			client.value.on("stream-subscribed", (evt) => {
				const remoteStream = evt.stream;
				remoteStream.play("remote-video");
			});
		},
		(err) => console.error("Join channel failed", err)
	);
};

const createLocalStream = () => {
	AgoraRTC.createMicrophoneAndCameraTracks()
		.then(([audioTrack, videoTrack]) => {
			localStream.value = { audioTrack, videoTrack };

			const localVideoElement = document.getElementById("local-video");
			if (localVideoElement) {
				localStream.value.videoTrack.play("local-video");
			} else {
				console.error("local-video element not found.");
			}

			client.value.publish([audioTrack, videoTrack]).catch((err) => {
				console.error("Failed to publish local stream:", err);
			});
		})
		.catch((err) => {
			console.error("Error initializing local tracks:", err);
			alert("Could not initialize camera and microphone. Please check permissions.");
		});
};

const endCall = () => {
	if (localStream.value) {
		localStream.value.audioTrack.stop();
		localStream.value.videoTrack.stop();
		localStream.value.audioTrack.close();
		localStream.value.videoTrack.close();
	}

	client.value
		.leave()
		.then(() => {
			console.log("Left the channel");
			callPlaced.value = false;
		})
		.catch((err) => {
			console.error("Error leaving the channel:", err);
		});
};

const toggleAudio = () => {
	if (localStream.value.audioTrack) {
		mutedAudio.value
			? localStream.value.audioTrack.setEnabled(true)
			: localStream.value.audioTrack.setEnabled(false);
		mutedAudio.value = !mutedAudio.value;
	}
};

const toggleVideo = () => {
	if (localStream.value.videoTrack) {
		mutedVideo.value
			? localStream.value.videoTrack.setEnabled(true)
			: localStream.value.videoTrack.setEnabled(false);
		mutedVideo.value = !mutedVideo.value;
	}
};

onMounted(() => {
	initUserOnlineChannel();
});
</script>

<style scoped>
/* Ensure the container takes up the full height */
.video-call-container {
	display: flex;
	justify-content: center; /* Centers content horizontally */
	align-items: center; /* Centers content vertically */
	height: 100vh; /* Full height of the viewport */
	position: relative;
}

/* Style for individual video feeds */
.video-feed {
	width: 100%; /* Allow video feed to scale */
	max-width: 500px; /* Maximum width */
	position: absolute; /* Allow absolute positioning */
	top: 50%; /* Center vertically */
	left: 50%; /* Center horizontally */
	transform: translate(-50%, -50%); /* Adjust to center the element */
}

.local-video,
.remote-video {
	width: 100%; /* Adjust video feed width */
	height: auto; /* Maintain aspect ratio */
}

/* Optional: Style for smaller video in the bottom left */
.small-video {
	width: 120px;
	height: auto;
	position: absolute;
	bottom: 20px;
	left: 20px;
	z-index: 10; /* Ensure it's on top of other videos */
}
</style>
