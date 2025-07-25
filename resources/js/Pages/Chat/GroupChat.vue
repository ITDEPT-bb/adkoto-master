<template>
    <Head title="Conversation" />
    <AuthenticatedLayout>
        <div
            class="flex-1 justify-between max-w-7xl mx-auto bg-white dark:bg-slate-950 dark:border-none rounded-lg border flex flex-col min-[360px]:h-[630px] max-[359px]:h-[620px] min-[400px]:h-[790px] min-[810px]:h-[1080px] md:h-[800px] lg:h-[1100px] xl:h-full scrollbar-thin"
        >
            <ChatHeader :group="groupChat" />
            <MessageList
                :messages="messages"
                :authUser="authUser"
                :group="groupChat"
            />
            <MessageInput
                :conversation="conversation"
                :user="authUser"
                @message-sent="fetchMessages"
            />
        </div>
    </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import ChatHeader from "@/Components/GroupChat/ChatHeader.vue";
import MessageList from "@/Components/GroupChat/MessageList.vue";
import MessageInput from "@/Components/GroupChat/MessageInput.vue";
import AuthenticatedLayout from "@/Layouts/ChatLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import { usePage, Head } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import axiosClient from "@/axiosClient.js";

const props = defineProps({
    groupChat: Object,
    messages: Array,
    conversation: Object,
});

const groupChat = props.groupChat;
const messages = ref(props.messages);
const conversation = props.conversation;
const authUser = usePage().props.auth.user;

const fetchMessages = async () => {
    try {
        const response = await axiosClient.get(
            `/group-chat/${groupChat.id}/messages`
        );
        messages.value = response.data;
    } catch (error) {
        console.error("Error fetching messages:", error);
    }
};

watch(
    () => props.notifications,
    (newNotifications) => {
        notifications.value = newNotifications;
    }
);

onMounted(() => {
    fetchMessages();
});
</script>
