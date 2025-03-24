<template>
    <div class="h-full overflow-hidden w-full p-2 bg-white rounded-lg shadow">
        <div class="flex h-full gap-4 lg:gap-0 flex-col sm:flex-row">
            <!-- Merged Chats Column -->
            <div class="flex-1 overflow-y-auto scrollbar-thin">
                <div
                    class="sticky flex justify-between top-0 bg-white shadow-sm z-10 p-2"
                >
                    <h2 class="text-xl font-bold">Recent Chats</h2>
                </div>
                <ul>
                    <li
                        v-for="chat in mergedChats.slice(0, 5)"
                        :key="chat.id"
                        class="mb-1 px-3 py-1 border-b border-gray-200"
                    >
                        <Link :href="getChatLink(chat)">
                            <div
                                class="flex items-center space-x-4 transition-transform duration-300 ease-in-out transform hover:scale-105 hover:bg-gray-100 p-2 rounded-lg"
                            >
                                <!-- Avatar/Group Icon -->
                                <div class="relative">
                                    <img
                                        :src="getChatAvatar(chat)"
                                        alt="Avatar"
                                        class="w-10 h-10 rounded-full object-cover"
                                    />
                                    <span
                                        v-if="isGroupChat(chat)"
                                        class="absolute -bottom-1 -right-1"
                                    >
                                        <div
                                            class="bg-blue-500 text-white p-1 rounded-full"
                                        >
                                            <svg
                                                class="w-3 h-3"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"
                                                />
                                            </svg>
                                        </div>
                                    </span>
                                </div>

                                <!-- Text Content -->
                                <div class="flex-1 overflow-hidden">
                                    <!-- Name/Title -->
                                    <h3
                                        class="text-lg flex items-start justify-between truncate"
                                    >
                                        <span class="truncate">
                                            {{ getChatTitle(chat) }}
                                        </span>
                                        <span
                                            v-if="showUnreadBadge(chat)"
                                            class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-1 flex-shrink-0"
                                        >
                                            {{
                                                formatUnreadCount(
                                                    chat.unread_count
                                                )
                                            }}
                                        </span>
                                    </h3>

                                    <!-- Last Message Preview -->
                                    <p
                                        class="text-sm text-gray-500 flex justify-between items-center truncate lg:pe-2"
                                        :class="{
                                            'font-bold text-black':
                                                isUnread(chat),
                                        }"
                                    >
                                        <span class="truncate">
                                            <span class="font-medium">
                                                {{ getMessagePrefix(chat) }}
                                            </span>
                                            {{
                                                chat.last_message ||
                                                "Start a conversation"
                                            }}
                                        </span>
                                        <span
                                            class="text-xs text-gray-400 ml-2"
                                        >
                                            {{
                                                formatTimestamp(chat.timestamp)
                                            }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

const authUser = usePage().props.auth.user;
dayjs.extend(relativeTime);

const props = defineProps({
    followings: Array,
    groupChats: Array,
});

// Helper functions
const isGroupChat = (chat) => chat.type === "group";
const isIndividualChat = (chat) => chat.type === "individual";

const mergedChats = computed(() => {
    const individuals = props.followings.map((chat) => ({
        ...chat,
        type: "individual",
        timestamp: chat.last_message_created_at || null, // Don't fallback to created_at
    }));

    const groups = props.groupChats.map((chat) => ({
        ...chat,
        type: "group",
        timestamp: chat.last_message_created_at || null, // Don't fallback to created_at
    }));

    return [...individuals, ...groups].sort((a, b) => {
        // Handle null timestamps as "oldest possible"
        const timeA = a.timestamp ? new Date(a.timestamp) : new Date(0);
        const timeB = b.timestamp ? new Date(b.timestamp) : new Date(0);
        return timeB - timeA;
    });
});

const getChatLink = (chat) => {
    return isGroupChat(chat)
        ? `/group-chat/${chat.id}`
        : `/chat/conversation/adktu/${chat.id}`;
};

const getChatAvatar = (chat) => {
    if (isGroupChat(chat)) {
        return chat.avatar_url || "/img/no_image.png";
    }
    return chat.avatar_url || "/img/default-avatar.png";
};

const getChatTitle = (chat) => {
    return isGroupChat(chat) ? chat.name : `${chat.name} ${chat.surname}`;
};

const getMessagePrefix = (chat) => {
    if (!chat.last_message_sender_id) return "";

    if (isGroupChat(chat)) {
        return chat.last_message_sender_id === authUser.id
            ? "You: "
            : `${chat.last_message_sender_name}: `;
    }

    return chat.last_message_sender_id === authUser.id ? "You: " : "";
};

const isUnread = (chat) => {
    if (isGroupChat(chat)) {
        return chat.unread_count > 0;
    }
    return (
        !chat.last_message_read_at &&
        chat.last_message_sender_id !== authUser.id
    );
};

const showUnreadBadge = (chat) => {
    return isGroupChat(chat)
        ? chat.unread_count > 0
        : chat.unread_count > 0 && chat.last_message_sender_id !== authUser.id;
};

const formatUnreadCount = (count) => {
    return count > 99 ? "99+" : count;
};

const formatTimestamp = (timestamp) => {
    if (!timestamp) return "";
    const date = new Date(timestamp);
    return dayjs(date).fromNow();
};
</script>
