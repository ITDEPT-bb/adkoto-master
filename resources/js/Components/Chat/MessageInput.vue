<template>
    <div class="border-t-2 border-gray-200 px-4 pt-4 mb-2 sm:mb-0">
        <div class="relative flex">
            <span class="absolute inset-y-0 flex items-center">
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-full h-12 w-12 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none"
                >
                    <MicIcon />
                </button>
            </span>
            <input
                v-model="newMessage"
                @keyup.enter="sendMessage"
                type="text"
                placeholder="Write your message!"
                class="w-full focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-600 pl-12 bg-gray-200 rounded-md py-3 resize-none"
                rows="4"
            />
            <div class="absolute right-0 items-center inset-y-0 hidden sm:flex">
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-full h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none"
                >
                    <PaperClipIcon />
                </button>
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-full h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none"
                >
                    <CameraIcon />
                </button>
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-full h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none"
                >
                    <EmojiIcon />
                </button>
                <button
                    @click="sendMessage"
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg px-4 py-3 transition duration-500 ease-in-out text-white bg-blue-500 hover:bg-blue-400 focus:outline-none"
                >
                    <span class="font-bold">Send</span>
                    <SendIcon />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineEmits } from "vue";
import axiosClient from "@/axiosClient.js";

import MicIcon from "@/Components/Icons/MicIcon.vue";
import PaperClipIcon from "@/Components/Icons/PaperClipIcon.vue";
import CameraIcon from "@/Components/Icons/CameraIcon.vue";
import EmojiIcon from "@/Components/Icons/EmojiIcon.vue";
import SendIcon from "@/Components/Icons/SendIcon.vue";

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    conversation: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["message-sent"]);
const newMessage = ref("");
const user = props.user;
const conversation = props.conversation;

// Function to send a new message
async function sendMessage() {
    if (!user.id || !conversation.id) {
        console.error("User or Conversation information is missing.");
        return;
    }

    if (newMessage.value.trim() === "") return;

    // Create a temporary message to optimistically update the UI
    const tempMessage = {
        id: Date.now(),
        message: newMessage.value,
        sender_id: user.id,
        conversation_id: conversation.id,
    };

    // Emit the new message to the parent component
    emit("message-sent", tempMessage);

    // Clear the input field
    newMessage.value = "";

    try {
        await axiosClient.post(
            `/chat/conversations/${conversation.id}/messages`,
            {
                receiver_id: user.id,
                conversation_id: conversation.id,
                message: tempMessage.message,
            }
        );
    } catch (error) {
        console.error("Error sending message:", error);
    }
}
</script>
