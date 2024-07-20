<template>
    <Head title="Social Media Website" />

    <AuthenticatedLayout>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 p-4 h-full">
            <!-- Following List Section -->
            <div
                class="col-span-1 lg:col-span-3 h-full overflow-y-auto bg-white p-4 rounded-lg shadow-md"
            >
                <h2 class="text-xl font-bold mb-4">Following</h2>
                <ul class="space-y-2">
                    <li
                        v-for="user in followings"
                        :key="user.id"
                        @click="selectUser(user)"
                        class="flex items-center space-x-2 cursor-pointer hover:bg-gray-100 p-2 rounded-lg"
                    >
                        <img
                            class="w-10 h-10 rounded-full object-cover"
                            :src="user.avatar_url"
                            alt="User Image"
                        />
                        <span>{{ user.name }} {{ user.surname }}</span>
                    </li>
                </ul>
            </div>

            <!-- Chat Window Section -->
            <div
                class="col-span-1 lg:col-span-6 h-full overflow-hidden bg-white rounded-lg shadow-md"
            >
                <div class="flex flex-col h-full">
                    <div class="bg-gray-white px-4 py-2 sticky top-0 z-10">
                        <div
                            v-if="selectedUser"
                            class="bg-white rounded-lg shadow-md mx-auto px-4 py-1 flex items-center space-x-4"
                        >
                            <img
                                class="w-10 h-10 rounded-full object-cover"
                                :src="selectedUser.avatar_url"
                                alt="User Image"
                            />
                            <div class="flex flex-col">
                                <h2 class="text-xl font-bold">
                                    {{ selectedUser.name }}
                                    {{ selectedUser.surname }}
                                </h2>
                                <span class="text-sm text-gray-600"
                                    >@{{ selectedUser.username }}</span
                                >
                            </div>
                        </div>

                        <div v-else class="text-gray-600">
                            Select a user to start a conversation.
                        </div>
                    </div>
                    <div
                        v-if="conversation"
                        class="flex-1 overflow-y-auto space-y-4 px-4"
                        ref="messageContainer"
                        @scroll="handleScroll"
                    >
                        <div
                            v-for="message in conversation.messages"
                            :key="message.id"
                            :class="{
                                'justify-end':
                                    message.sender_id !== selectedUser.id,
                                'justify-start':
                                    message.sender_id === selectedUser.id,
                            }"
                            class="flex mt-2"
                        >
                            <div
                                :class="{
                                    'bg-blue-100 text-right':
                                        message.sender_id !== selectedUser.id,
                                    'bg-gray-100 text-left':
                                        message.sender_id === selectedUser.id,
                                }"
                                class="p-3 rounded-lg max-w-full mx-2 break-words"
                            >
                                <p class="text-sm">
                                    <strong
                                        >{{
                                            message.sender_id !==
                                            selectedUser.id
                                                ? "You"
                                                : selectedUser.name
                                        }}:</strong
                                    >
                                    <br />
                                    {{ message.message }}
                                    <br />
                                    <span class="text-xs text-gray-500">{{
                                        formatMessageTimestamp(
                                            message.created_at
                                        )
                                    }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div
                        v-else
                        class="flex-1 flex items-center justify-center text-gray-500"
                    >
                        Select a user to start a conversation.
                    </div>
                    <div class="mt-4 px-4">
                        <div class="relative">
                            <textarea
                                v-if="conversation"
                                v-model="newMessage"
                                @keyup.enter="sendMessage"
                                class="w-full p-2 mb-3 border rounded-lg resize-none block"
                                placeholder="Type a message..."
                            ></textarea>
                            <button
                                v-if="conversation"
                                @click="sendMessage"
                                class="absolute inset-y-0 right-0 px-4 py-2 bg-blue-500 text-white rounded-md"
                            >
                                Send
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Info Section -->
            <div
                class="col-span-1 lg:col-span-3 h-full overflow-y-auto bg-white p-4 rounded-lg shadow-md"
            >
                <h2 class="text-xl font-bold mb-4">User Info</h2>
                <div v-if="selectedUser" class="flex items-center space-x-4">
                    <img
                        class="w-10 h-10 rounded-full object-cover"
                        :src="selectedUser.avatar_url"
                        alt="User Image"
                    />
                    <div>
                        <h3 class="text-lg font-semibold">
                            {{ selectedUser.name }} {{ selectedUser.surname }}
                        </h3>
                    </div>
                </div>
                <div v-else class="text-gray-500">
                    Select a user to view their information.
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from "vue";
import axiosClient from "@/axiosClient.js";
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/ChatLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

const selectedUser = ref(null);
const conversation = ref(null);
const newMessage = ref("");
const messageContainer = ref(null);

const props = defineProps({
    followings: Array,
});

// Function to scroll to the bottom of the chat window
async function scrollToBottom() {
    await nextTick();
    if (messageContainer.value) {
        messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
    }
}

// Function to format message timestamp
function formatMessageTimestamp(timestamp) {
    const now = new Date();
    const messageTime = new Date(timestamp);
    const diffTime = now - messageTime;

    const diffSeconds = Math.floor(diffTime / 1000);

    const minute = 60;
    const hour = minute * 60;
    const day = hour * 24;

    if (diffSeconds < minute) {
        return "just now";
    } else if (diffSeconds < 2 * minute) {
        return "a minute ago";
    } else if (diffSeconds < hour) {
        return Math.floor(diffSeconds / minute) + " minutes ago";
    } else if (diffSeconds < 2 * hour) {
        return "an hour ago";
    } else if (diffSeconds < day) {
        return Math.floor(diffSeconds / hour) + " hours ago";
    } else {
        return messageTime.toLocaleDateString(undefined, {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "numeric",
            minute: "numeric",
        });
    }
}

// Function to select a user and fetch their conversation
async function selectUser(user) {
    try {
        selectedUser.value = user;
        const response = await axiosClient.get(
            `/chat/conversations/${user.id}`
        );
        conversation.value = response.data;
        await scrollToBottom();
    } catch (error) {
        console.error("Error fetching conversation:", error);
    }
}

// Function to send a new message
async function sendMessage() {
    try {
        if (newMessage.value.trim() === "") return;

        const response = await axiosClient.post(
            `/chat/conversations/${selectedUser.value.id}/messages`,
            {
                receiver_id: selectedUser.value.id,
                conversation_id: conversation.value.conversation.id,
                message: newMessage.value,
            }
        );

        conversation.value.messages.push(response.data);
        newMessage.value = "";
        await scrollToBottom();
    } catch (error) {
        console.error("Error sending message:", error);
    }
}

// Polling for new messages
const startPolling = () => {
    setInterval(async () => {
        if (selectedUser.value && conversation.value) {
            try {
                const response = await axiosClient.get(
                    `/chat/conversations/${selectedUser.value.id}`
                );
                conversation.value = response.data;
                await scrollToBottom();
            } catch (error) {
                console.error("Error fetching updated conversation:", error);
            }
        }
    }, 2000);
};

onMounted(() => {
    scrollToBottom();
    startPolling();
});
</script>

<style scoped>
#messageContainer {
    scroll-behavior: smooth;
}
</style>
