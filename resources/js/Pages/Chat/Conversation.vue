<template>
    <Head title="Conversation" />
    <AuthenticatedLayout>
        <div
            class="flex-1 justify-between max-w-7xl mx-auto bg-white dark:bg-slate-950 dark:border-none rounded-lg border flex flex-col min-[360px]:h-[630px] max-[359px]:h-[620px] min-[400px]:h-[790px] min-[810px]:h-[1080px] md:h-[800px] lg:h-[1100px] xl:h-full scrollbar-thin"
        >
            <!-- <div
            class="flex-1 justify-between max-w-7xl mx-auto bg-white dark:bg-slate-950 dark:border-none rounded-lg border flex flex-col min-[360px]:h-[630px] min-[370px]:h-[320px] max-[359px]:h-[620px] min-[360px]:h-[700px] min-[400px]:h-[790px] md:h-[920px] min-[810px]:h-[1080px] lg:h-[1250px] xl:h-full scrollbar-thin"
        > -->
            <!-- <div
            class="flex-1 my-2 pb-6 justify-between max-w-7xl mx-auto bg-white dark:bg-slate-950 dark:border-none rounded-lg border flex flex-col h-full scrollbar-thin"
        > -->
            <ChatHeader :user="user" agora_id="{{ env('AGORA_APP_ID') }}" />
            <template v-if="!isBlockedByAuthUser && !isBlockedByOtherUser">
                <MessageList
                    :messages="messages"
                    :authUser="authUser"
                    :user="user"
                    :conversation="conversation"
                />
                <MessageInput
                    :user="user"
                    :conversation="conversation"
                    @message-sent="addMessage"
                />
            </template>
            <template v-else-if="isBlockedByAuthUser">
                <div
                    class="flex items-center justify-center h-64 text-gray-600"
                >
                    <p>
                        You cannot send messages here because you blocked this
                        user. (Visit their Profile to Unblock them.)
                    </p>
                </div>
            </template>
            <template v-else>
                <div
                    class="flex items-center justify-center h-64 text-gray-600"
                >
                    <p>
                        You cannot send messages because this conversation is
                        restricted.
                    </p>
                </div>
            </template>
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
    isBlockedByAuthUser: Boolean,
    isBlockedByOtherUser: Boolean,
});

const user = props.user;
const messages = ref(props.messages);
const conversation = props.conversation;
const authUser = usePage().props.auth.user;

const fetchMessages = async () => {
    try {
        const response = await axiosClient.get(
            `/chat/conversations/${conversation.id}/messages`
        );
        messages.value = response.data;
    } catch (error) {
        console.error("Error fetching messages:", error);
    }
};

const markAsRead = async () => {
    try {
        await axios.post(`/chat/mark-as-read/${conversation.id}`);
    } catch (error) {
        console.error("Failed to mark messages as read:", error);
    }
};

const addMessage = (newMessage) => {
    messages.value.push(newMessage);
    scrollToBottom();
};

const scrollToBottom = () => {
    const messageContainer = document.getElementById("messages");
    if (messageContainer) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
};

onMounted(() => {
    fetchMessages();
    markAsRead();
    scrollToBottom();
});
</script>
