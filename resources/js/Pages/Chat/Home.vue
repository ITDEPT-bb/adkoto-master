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
                            class="w-10 h-10 rounded-full"
                            :src="user.avatar_url"
                            alt="User Image"
                        />
                        <span>{{ user.name }}</span>
                    </li>
                </ul>
            </div>

            <!-- Chat Window Section -->
            <div
                class="col-span-1 lg:col-span-6 h-full overflow-y-auto bg-white p-4 rounded-lg shadow-md"
            >
                <h2 class="text-xl font-bold mb-4">Chat</h2>
                <div
                    v-if="conversation"
                    class="flex-1 overflow-y-auto space-y-4"
                >
                    <div
                        v-for="message in conversation.messages"
                        :key="message.id"
                        :class="{
                            'bg-blue-100':
                                message.sender_id !== selectedUser.id,
                            'bg-gray-100':
                                message.sender_id === selectedUser.id,
                        }"
                        class="p-3 rounded-lg"
                    >
                        <p>
                            <strong
                                >{{
                                    message.sender_id !== selectedUser.id
                                        ? "You"
                                        : selectedUser.name
                                }}:</strong
                            >
                            {{ message.message }}
                        </p>
                    </div>
                </div>
                <div
                    v-else
                    class="flex-1 flex items-center justify-center text-gray-500"
                >
                    Select a user to start a conversation.
                </div>
                <div class="mt-4">
                    <input
                        v-if="conversation"
                        v-model="newMessage"
                        @keyup.enter="sendMessage"
                        type="text"
                        class="w-full p-2 border rounded-lg"
                        placeholder="Type a message..."
                    />
                </div>
            </div>

            <!-- User Info Section -->
            <div
                class="col-span-1 lg:col-span-3 h-full overflow-y-auto bg-white p-4 rounded-lg shadow-md"
            >
                <h2 class="text-xl font-bold mb-4">User Info</h2>
                <div v-if="selectedUser" class="flex items-center space-x-4">
                    <img
                        class="w-10 h-10 rounded-full"
                        :src="selectedUser.image"
                        alt="User Image"
                    />
                    <div>
                        <h3 class="text-lg font-semibold">
                            {{ selectedUser.name }}
                        </h3>
                        <!-- <p class="text-gray-600">{{ selectedUser.email }}</p> -->
                    </div>
                </div>
                <div v-else class="text-gray-500">
                    Select a user to view their information.
                </div>
                <div v-if="selectedUser" class="mt-6">
                    <h4 class="text-lg font-semibold mb-2">About</h4>
                    <p class="text-gray-700">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        Nulla et euismod nulla. Curabitur feugiat, tortor non
                        consequat finibus, justo purus auctor massa, nec semper
                        lorem quam in massa.
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import { Head } from "@inertiajs/vue3";
import axiosClient from "@/axiosClient.js";
import AuthenticatedLayout from "@/Layouts/ChatLayout.vue";

const selectedUser = ref(null);
const conversation = ref(null);
const newMessage = ref("");
const props = defineProps({
    followings: Array,
});

async function selectUser(user) {
    try {
        selectedUser.value = user;
        const response = await axiosClient.get(
            `/chat/conversations/${user.id}`
        );
        conversation.value = response.data;
        // Ensure selectedUser.image is correctly populated from the response
        selectedUser.value.image = user.avatar_url; // Assuming the user object has avatar_url property
    } catch (error) {
        console.error("Error fetching conversation:", error);
        // Handle error: show message, reset conversation, etc.
    }
}

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
    } catch (error) {
        console.error("Error sending message:", error);
        // Handle error: show message to user, retry logic, etc.
    }
}
</script>
