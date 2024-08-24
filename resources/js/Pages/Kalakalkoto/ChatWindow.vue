<template>
    <Head title="Conversation" />
    <AuthenticatedLayout>
        <div
            class="flex-1 p:2 sm:p-6 justify-between max-w-7xl mx-auto flex flex-col h-full"
        >
            <ChatHeader :user="user" />
            <!-- Display Item Information -->
            <div class="p-4 bg-gray-100 rounded-md">
                <h3 class="text-lg font-bold">Item: {{ item.name }}</h3>
                <p class="text-sm text-gray-700">Price: {{ item.price }}</p>
                <p class="text-sm text-gray-700">
                    Description: {{ item.description }}
                </p>
            </div>
            <!-- Messages and Input -->
            <MessageList
                :messages="messages"
                :authUser="authUser"
                :user="user"
                :conversation="conversation"
            />
            <MessageInput
                :user="user"
                :conversation="conversation"
                @message-sent="fetchMessages"
            />
        </div>
    </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import ChatHeader from "@/Components/Kalakalkoto/ChatHeader.vue";
import MessageList from "@/Components/Kalakalkoto/MessageList.vue";
import MessageInput from "@/Components/Kalakalkoto/MessageInput.vue";
import AuthenticatedLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import { usePage, Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axiosClient from "@/axiosClient.js";

// Props
const props = defineProps({
    user: Object,
    messages: Array,
    conversation: Object,
    item: Object,
});

const user = props.user;
const messages = ref(props.messages);
const conversation = props.conversation;
const item = props.item;
const authUser = usePage().props.auth.user;

const fetchMessages = async () => {
    try {
        const response = await axiosClient.get(
            `/kalakalkoto/chat/conversations/${conversation.id}/messages`
        );
        messages.value = response.data;
    } catch (error) {
        console.error("Error fetching messages:", error);
    }
};

onMounted(() => {
    fetchMessages();
});
</script>
