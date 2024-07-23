<template>
    <div class="border-t-2 border-gray-200 px-4 pt-4 mb-2 sm:mb-0">
        <div
            class="flex items-center px-3 py-2 rounded-lg bg-gray-50 dark:bg-gray-700"
        >
            <button
                type="button"
                class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600"
            >
                <MicIcon />
                <span class="sr-only">Record audio</span>
            </button>
            <button
                type="button"
                class="p-2 text-gray-500 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600"
            >
                <PaperClipIcon />
                <span class="sr-only">Attach file</span>
            </button>
            <button
                type="button"
                class="p-2 text-gray-500 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600"
            >
                <CameraIcon />
                <span class="sr-only">Upload image</span>
            </button>
            <button
                type="button"
                class="p-2 text-gray-500 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600"
            >
                <EmojiIcon />
                <span class="sr-only">Add emoji</span>
            </button>
            <textarea
                v-model="newMessage"
                @keyup.enter="sendMessage"
                id="chat"
                rows="1"
                class="block mx-4 p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Write your message..."
            ></textarea>
            <button
                @click="sendMessage"
                type="button"
                class="inline-flex justify-center p-2 text-blue-600 rounded-full cursor-pointer hover:bg-blue-100 dark:text-blue-500 dark:hover:bg-gray-600"
            >
                <SendIcon />
                <span class="sr-only">Send message</span>
            </button>
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
