<template>
    <div class="relative">
        <!-- Notification Bell Icon/Button -->
        <button @click="showDropdown = !showDropdown" class="relative block">
            <img
                :src="notificationIcon"
                class="h-8 w-auto"
                alt="Notifications"
            />
            <!-- Unread count indicator -->
            <div
                v-if="unreadCount > 0"
                class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full"
            ></div>
        </button>

        <!-- Dropdown Panel -->
        <transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-if="showDropdown"
                @click.away="showDropdown = false"
                class="origin-top-right absolute right-0 mt-12 w-[400px] rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
            >
                <div class="py-1">
                    <div v-if="loading" class="p-4 text-center text-gray-500">
                        Loading...
                    </div>
                    <template v-else>
                        <template v-if="notifications.length === 0">
                            <div class="p-4 text-center text-gray-500">
                                No notifications
                            </div>
                        </template>
                        <ul v-else class="divide-y divide-gray-200">
                            <li
                                v-for="notification in notifications.slice(
                                    0,
                                    6
                                )"
                                :key="notification.id"
                                class="py-2"
                            >
                                <div class="hover:bg-gray-100">
                                    <a
                                        :href="notification.url"
                                        class="flex items-center hover:cursor-pointer justify-between px-4 py-2 text-sm text-gray-700"
                                        @click="markAsRead(notification)"
                                    >
                                        <div
                                            class="flex items-center space-x-2"
                                        >
                                            <!-- Notification Icon based on type -->
                                            <template
                                                v-if="
                                                    notification.type ===
                                                    'App\Notifications\FollowUser'
                                                "
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-6 w-6 text-blue-500"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                                    ></path>
                                                </svg>
                                            </template>
                                            <!-- Add more templates for different notification types -->
                                            {{ notification.data.message }}
                                        </div>
                                    </a>
                                    <p
                                        class="text-gray-400 hover:cursor-pointer text-xs flex items-center justify-between px-4"
                                    >
                                        {{
                                            dayjs(
                                                notification.created_at
                                            ).fromNow()
                                        }}
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </template>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axiosClient from "@/axiosClient.js";

import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(relativeTime);

const showDropdown = ref(false);
const notifications = ref([]);
const loading = ref(true);

import notificationIcon from "/public/img/icons/notification.png";

const unreadCount = computed(() => {
    return notifications.value.filter((notification) => !notification.read_at)
        .length;
});

const fetchNotifications = async () => {
    try {
        const response = await axiosClient.get("/notifications");
        notifications.value = response.data;
    } catch (error) {
        console.error("Failed to fetch notifications:", error);
        notifications.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(async () => {
    await fetchNotifications();
});

const markAsRead = (notification) => {
    console.log("Marking notification as read:", notification);
};

const formatDate = (datetime) => {
    return new Date(datetime).toLocaleString();
};
</script>

<style scoped>
/* Add your scoped styles here */
</style>
