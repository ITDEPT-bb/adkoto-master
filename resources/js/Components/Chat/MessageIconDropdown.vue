<template>
	<div class="relative">
		<!-- Notification Bell Icon/Button -->
		<button
			@click="showDropdown = !showDropdown"
			class="relative block">
			<img
				:src="messageIcon"
				class="md:h-auto md:w-12 xl:w-[58px]"
				alt="Notifications" />
			<!-- Unread count indicator -->
			<div
				v-if="unreadCount > 0"
				class="absolute top-0 right-0 h-5 w-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
				{{ unreadCount }}
			</div>
		</button>

		<!-- Dropdown Panel -->
		<transition
			enter-active-class="transition ease-out duration-100"
			enter-from-class="transform opacity-0 scale-95"
			enter-to-class="transform opacity-100 scale-100"
			leave-active-class="transition ease-in duration-75"
			leave-from-class="transform opacity-100 scale-100"
			leave-to-class="transform opacity-0 scale-95">
			<div
				v-if="showDropdown"
				@click.away="showDropdown = false"
				class="origin-top-right absolute right-0 mt-12 w-[400px] rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
				<div class="py-1">
					<ChatListDropdown
						:followings="messageUsers"
						:groupChats="groupChats" />
					<div class="py-2 px-4 text-center">
						<Link
							:href="route('chat.index')"
							class="text-sm font-medium w-full text-blue-600 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md px-4 py-2 hover:bg-gray-200 transition-colors block text-center">
							View all Messages
						</Link>
					</div>
				</div>
			</div>
		</transition>
	</div>
</template>

<script setup>
import { defineProps, ref, onMounted, onUnmounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import axiosClient from "@/axiosClient.js";
import ChatListDropdown from "@/Components/Chat/ChatListDropdown.vue";

import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

import messageIcon from "/public/img/icons/message.png";

dayjs.extend(relativeTime);

const showDropdown = ref(false);
const unreadCount = ref(0);
const loading = ref(true);

const props = defineProps({
	messageUsers: {
		type: Array,
		required: true,
	},
	// participants: {
	// 	type: Array,
	// 	required: true,
	// },
	groupChats: {
		type: Array,
		required: true,
	},
});

const messageUsers = ref(props.messageUsers);
const groupChats = ref(props.groupChats);

const fetchLatestMessages = async () => {
	try {
		const response = await axios.get("/chat/latest-messages");
		messageUsers.value = response.data.messageUsers;
		groupChats.value = response.data.groupChats;
	} catch (error) {
		console.error("Error fetching latest messages", error);
	}
};

const fetchUnreadCount = async () => {
	try {
		const response = await axiosClient.get("/chat/unread-count");
		unreadCount.value = response.data.unreadCount;
	} catch (error) {
		console.error("Error fetching unread count", error);
	}
};

onMounted(() => {
	fetchLatestMessages();
	const interval = setInterval(fetchLatestMessages, 1000);

	fetchUnreadCount();

	const unreadInterval = setInterval(fetchUnreadCount, 1000);

	onUnmounted(() => {
		clearInterval(interval);
		clearInterval(unreadInterval);
	});
});
</script>
