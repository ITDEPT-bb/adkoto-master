<template>
    <div>
        <div
            v-for="message in messages"
            :key="message.id"
            :class="{
                'justify-end': message.sender_id !== selectedUserId,
                'justify-start': message.sender_id === selectedUserId,
            }"
            class="flex mt-2"
        >
            <div
                :class="{
                    'bg-blue-100 text-right':
                        message.sender_id !== selectedUserId,
                    'bg-gray-100 text-left':
                        message.sender_id === selectedUserId,
                }"
                class="p-3 rounded-lg max-w-full mx-2 break-words"
            >
                <p class="text-sm">
                    <strong
                        >{{
                            message.sender_id !== selectedUserId
                                ? "You"
                                : selectedUser.name
                        }}:</strong
                    ><br />
                    {{ message.message }}<br />
                    <span class="text-xs text-gray-500">{{
                        formatMessageTimestamp(message.created_at)
                    }}</span>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, computed } from "vue";
import { useContext } from "vue";

const props = defineProps({
    messages: Array,
    selectedUserId: Number,
});

const { selectedUser } = useContext();

const formatMessageTimestamp = (timestamp) => {
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
};
</script>
