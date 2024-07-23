<template>
    <div
        ref="messageContainer"
        id="messages"
        class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch"
        @scroll="onScroll"
    >
        <div v-for="message in messages" :key="message.id" class="chat-message">
            <div
                class="flex items-end"
                :class="{
                    'justify-end': message.sender_id === authUser.id,
                    'justify-start': message.sender_id !== authUser.id,
                }"
            >
                <div
                    class="flex flex-col space-y-2 text-xs mx-2"
                    :class="{
                        'order-1 items-end': message.sender_id === authUser.id,
                        'order-2 items-start':
                            message.sender_id !== authUser.id,
                    }"
                >
                    <div>
                        <span
                            class="px-4 py-2 rounded-lg inline-block break-words max-w-lg"
                            :class="{
                                'bg-blue-600 text-white':
                                    message.sender_id === authUser.id,
                                'bg-gray-300 text-gray-600':
                                    message.sender_id !== authUser.id,
                            }"
                        >
                            {{ message.message }}
                        </span>
                    </div>
                </div>
                <img
                    v-if="message.sender_id !== authUser.id"
                    :src="user.avatar_url"
                    alt="Profile Picture"
                    class="w-6 h-6 rounded-full"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick, watch } from "vue";
import axiosClient from "@/axiosClient.js";

const props = defineProps({
    messages: Array,
    authUser: Object,
    user: Object,
    conversation: Object,
});

const messages = ref(props.messages);
const authUser = props.authUser;
const user = props.user;
const conversation = props.conversation;
const messageContainer = ref(null);

let intervalId = null;
const isUserScrolling = ref(false);

// Function to scroll to the bottom of the chat window
const scrollToBottom = async () => {
    await nextTick();
    if (messageContainer.value) {
        messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
    }
};

// Fetch messages
const fetchMessages = async () => {
    try {
        const response = await axiosClient.get(
            `/chat/conversations/${conversation.id}/messages`
        );
        messages.value = response.data;

        if (!isUserScrolling.value) {
            scrollToBottom();
        }
    } catch (error) {
        console.error("Error fetching messages:", error);
    }
};

// Start polling for new messages
const startPolling = () => {
    intervalId = setInterval(fetchMessages, 5000); // Increase interval
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
    startPolling();
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
