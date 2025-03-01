<template>
	<div
		ref="messageContainer"
		id="messages"
		class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thin scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch"
		@scroll="onScroll">
		<div
			v-for="message in messages"
			:key="message.id"
			class="chat-message">
			<div
				v-if="message.type === 'system'"
				class="flex items-center justify-center">
				<div class="text-xs text-gray-500 italic">
					{{ message.message }}
				</div>
			</div>

			<div
				v-else
				class="flex items-center"
				:class="{
					'justify-end': message.sender_id === authUser.id,
					'justify-start': message.sender_id !== authUser.id,
				}">
				<div
					class="flex flex-col space-y-0.5 text-xs mx-2"
					:class="{
						'order-1 items-end': message.sender_id === authUser.id,
						'order-2 items-start': message.sender_id !== authUser.id,
					}">
					<span
						v-if="message.sender_id !== authUser.id"
						class="text-xs font-bold text-gray-500 mx-1">
						{{ message.sender.name }} {{ message.sender.surname }}
					</span>
					<div
						class="max-w-lg p-2 rounded-lg"
						:class="{
							'bg-blue-600 text-white': message.sender_id === authUser.id,
							'bg-gray-300 text-gray-600': message.sender_id !== authUser.id,
						}">
						<span
							v-if="message.message"
							class="block break-words">
							{{ message.message }}
						</span>

						<!-- Display attachments if available -->
						<div
							v-if="message.attachments && message.attachments.length > 0"
							class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mt-2">
							<div
								v-for="attachment in message.attachments"
								:key="attachment.id"
								class="rounded-lg inline-block m-1 break-words max-w-lg">
								<!-- Render images -->
								<div v-if="attachment.mime.startsWith('image/')">
									<img
										:src="`/storage/${attachment.path}`"
										alt="Image"
										class="rounded-lg w-[100px] h-[100px] object-cover cursor-pointer"
										@click="openImageModal(`/storage/${attachment.path}`)" />
								</div>

								<!-- Render videos -->
								<div v-else-if="attachment.mime.startsWith('video/')">
									<video
										:src="`/storage/${attachment.path}`"
										controls
										class="rounded-lg w-[100px] h-[100px] object-cover"></video>
								</div>

								<!-- Render other file types -->
								<div v-else>
									<Link
										:href="`/storage/${attachment.path}`"
										target="_blank"
										class="text-blue-500 underline">
										{{ attachment.name }}
									</Link>
								</div>
							</div>
						</div>
					</div>
					<small
						v-if="showTime"
						class="text-gray-400 pl-1.5"
						>{{ formatTime(message.created_at) }}</small
					>
				</div>
				<!-- <img
					v-if="message.sender_id !== authUser.id"
					:src="message.sender.avatar_url"
					alt="Profile Picture"
					class="w-6 h-6 rounded-full" /> -->
				<img
					v-if="message.sender_id !== authUser.id && message.sender"
					:src="message.sender.avatar_url || '/img/default_avatar.png'"
					alt="Profile Picture"
					class="w-6 h-6 rounded-full" />
			</div>
		</div>
		<!-- Image Modal -->
		<ImageModal
			:src="currentImageSrc"
			:isVisible="isImageModalVisible"
			@update:isVisible="isImageModalVisible = $event" />
	</div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick, watch } from "vue";
import axiosClient from "@/axiosClient.js";
import { Link } from "@inertiajs/vue3";
import ImageModal from "@/Components/Chat/ImageModal.vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(relativeTime);

const props = defineProps({
	messages: Array,
	authUser: Object,
	group: Object,
	// conversation: Object,
	showTime: {
		type: Boolean,
		default: true,
	},
});
const emit = defineEmits(["onScroll"]);

const isImageModalVisible = ref(false);
const currentImageSrc = ref("");

const openImageModal = (src) => {
	currentImageSrc.value = src;
	isImageModalVisible.value = true;
};

const messages = ref(props.messages);
const authUser = props.authUser;
const user = props.user;
const group = props.group;
const messageContainer = ref(null);
// const conversation = props.conversation;

// console.log("Messages: ", conversation.id);

let intervalId = null;
const isUserScrolling = ref(false);

function formatTime(postDate) {
	const now = dayjs();
	const postTime = dayjs(postDate);

	if (now.diff(postTime, "day") < 1) {
		return postTime.fromNow();
	} else if (now.diff(postTime, "day") === 1) {
		return `Yesterday at ${postTime.format("h:mm A")}`;
	} else if (now.diff(postTime, "day") < 7) {
		return `${postTime.format("dddd [at] h:mm A")}`;
	} else {
		return postTime.format("MMMM D, YYYY [at] h:mm A");
	}
}

const scrollToBottom = async () => {
	await nextTick();
	if (messageContainer.value) {
		messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
	}
};

const fetchMessages = async () => {
	try {
		const response = await axiosClient.get(`/group-chat/${group.id}/messages`);
		messages.value = response.data;

		if (!isUserScrolling.value) {
			scrollToBottom();
		}
	} catch (error) {
		console.error("Error fetching messages:", error);
	}
};

const startPolling = () => {
	intervalId = setInterval(fetchMessages, 5000);
};

const onScroll = () => {
	if (messageContainer.value) {
		const bottomThreshold = 50;
		const isAtBottom =
			messageContainer.value.scrollHeight -
				messageContainer.value.scrollTop -
				messageContainer.value.clientHeight <
			bottomThreshold;

		isUserScrolling.value = !isAtBottom;
	}
};

onMounted(() => {
	// startPolling();
	window.Echo.private(`groupchat.groups.${group.id}`).listen("GroupChatMessageSent", (event) => {
		// console.log("Message received: ", event.message);
		// console.log("Sender data: ", event.message.sender);
		messages.value.push(event.message);
		scrollToBottom();
	});
	scrollToBottom();
});

onBeforeUnmount(() => {
	if (intervalId) {
		clearInterval(intervalId);
	}
});

watch(
	() => props.messages,
	(newMessages) => {
		if (newMessages) {
			messages.value = newMessages;
			scrollToBottom();
		}
	}
);

const handleMessageSent = (message) => {
	messages.value.push(message);
	scrollToBottom();
};

const handleMessageSentEvent = (event) => {
	handleMessageSent(event.detail);
};

onMounted(() => {
	document.addEventListener("message-sent", handleMessageSentEvent);
});

onBeforeUnmount(() => {
	document.removeEventListener("message-sent", handleMessageSentEvent);
});
</script>

<style scoped>
#messages {
	scroll-behavior: smooth;
}
</style>
