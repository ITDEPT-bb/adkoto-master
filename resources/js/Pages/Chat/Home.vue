<template>
	<Head title="Chat" />

	<AuthenticatedLayout>
		<div class="home-page flex h-full overflow-y-auto container p-4 mx-auto scrollbar-thin">
			<FollowingList
				:followings="messageUsers"
				:groupChats="groupChats" />
		</div>
	</AuthenticatedLayout>
	<UpdateProfileReminder />
</template>

<script setup>
import { defineProps, ref, onMounted, onUnmounted } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/ChatLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import FollowingList from "@/Components/Chat/FollowingList.vue";
import SearchModal from "@/Components/Chat/SearchModal.vue";

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

const authUser = usePage().props.auth.user;

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

onMounted(() => {
	// fetchLatestMessages();
	// const interval = setInterval(fetchLatestMessages, 1000);

	window.Echo.private(`chat.home.${authUser.id}`).listen("RefreshChatHome", (event) => {
		fetchLatestMessages();
	});

	onUnmounted(() => {
		// clearInterval(interval);
	});
});
</script>
