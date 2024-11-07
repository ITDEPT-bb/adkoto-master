<template>
	<Head title="Conversation" />
	<AuthenticatedLayout>
		<div
			class="flex-1 my-2 pb-6 justify-between max-w-7xl mx-auto bg-white rounded-lg border flex flex-col h-full">
			<ChatHeader :user="user" />
			<MessageList
				:messages="messages"
				:authUser="authUser"
				:user="user"
				:conversation="conversation" />
			<MessageInput
				:user="user"
				:conversation="conversation"
				@message-sent="fetchMessages" />
		</div>
	</AuthenticatedLayout>
	<UpdateProfileReminder />
</template>

<script setup>
import ChatHeader from "@/Components/Chat/ChatHeader.vue";
import MessageList from "@/Components/Chat/MessageList.vue";
import MessageInput from "@/Components/Chat/MessageInput.vue";
import AuthenticatedLayout from "@/Layouts/ChatLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import { usePage, Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axiosClient from "@/axiosClient.js";

const props = defineProps({
	user: Object,
	messages: Array,
	conversation: Object,
});

const user = props.user;
const messages = ref(props.messages);
const conversation = props.conversation;
const authUser = usePage().props.auth.user;

const fetchMessages = async () => {
	try {
		const response = await axiosClient.get(`/chat/conversations/${conversation.id}/messages`);
		messages.value = response.data;
	} catch (error) {
		console.error("Error fetching messages:", error);
	}
};

onMounted(() => {
	fetchMessages();
});
</script>
