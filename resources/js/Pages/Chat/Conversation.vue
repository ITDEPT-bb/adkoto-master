<template>
    <Head title="Conversation" />

    <AuthenticatedLayout>
        <div class="flex-1 p:2 sm:p-6 justify-between flex flex-col h-full">
            <!-- Header Section -->
            <div
                class="flex sm:items-center justify-between py-3 border-b-2 border-gray-200"
            >
                <!-- User Info -->
                <div class="relative flex items-center space-x-4">
                    <!-- Back Button -->
                    <Link :href="route('chat.index')">
                        <button
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6 text-gray-600"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                    </Link>
                    <div class="relative">
                        <span class="absolute text-green-500 right-0 bottom-0">
                            <svg width="20" height="20">
                                <circle
                                    cx="8"
                                    cy="8"
                                    r="8"
                                    fill="currentColor"
                                ></circle>
                            </svg>
                        </span>
                        <img
                            :src="user.avatar_url"
                            alt=""
                            class="w-10 sm:w-12 h-10 sm:h-12 rounded-full"
                        />
                    </div>
                    <div class="flex flex-col leading-tight">
                        <div
                            class="text-1xl font-semibold mt-1 flex items-center"
                        >
                            <span class="text-gray-700 mr-3"
                                >{{ user.name }} {{ user.surname }}</span
                            >
                        </div>
                        <span class="text-sm text-gray-600"
                            >@{{ user.username }}</span
                        >
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="flex items-center space-x-2 px-2">
                    <!-- <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none"
                    >
                        <SearchIcon />
                    </button>
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none"
                    >
                        <HeartIcon />
                    </button>
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none"
                    >
                        <BellIcon />
                    </button> -->
                    <Link :href="route('profile', user.username)">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none"
                        >
                            <ProfileIcon />
                        </button>
                    </Link>
                </div>
            </div>

            <!-- Message List -->
            <div
                id="messages"
                class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch"
            >
                <div
                    v-for="message in messages"
                    :key="message.id"
                    class="chat-message"
                >
                    <div
                        class="flex items-end"
                        :class="{
                            'justify-end': message.sender_id === authUser.id,
                            'justify-start': message.sender_id !== authUser.id,
                        }"
                    >
                        <div
                            class="flex flex-col space-y-2 text-xs max-w-xs mx-2"
                            :class="{
                                'order-1 items-end':
                                    message.sender_id === authUser.id,
                                'order-2 items-start':
                                    message.sender_id !== authUser.id,
                            }"
                        >
                            <div>
                                <span
                                    class="px-4 py-2 rounded-lg inline-block"
                                    :class="{
                                        'bg-blue-600 text-white':
                                            message.sender_id === authUser.id,
                                        'bg-gray-300 text-gray-600':
                                            message.sender_id !== authUser.id,
                                    }"
                                    >{{ message.message }}</span
                                >
                            </div>
                        </div>
                        <!-- <img :src="message.sender.profilePicture" alt="Profile Picture" class="w-6 h-6 rounded-full" :class="{'order-2': message.sender_id === authUser.id, 'order-1': message.sender_id !== authUser.id}" /> -->
                        <img
                            v-if="message.sender_id !== authUser.id"
                            :src="user.avatar_url"
                            alt="Profile Picture"
                            class="w-6 h-6 rounded-full"
                        />
                    </div>
                </div>
            </div>

            <!-- Message Input -->
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
                        type="text"
                        placeholder="Write your message!"
                        class="w-full focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-600 pl-12 bg-gray-200 rounded-md py-3"
                    />
                    <div
                        class="absolute right-0 items-center inset-y-0 hidden sm:flex"
                    >
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
                            type="button"
                            class="inline-flex items-center justify-center rounded-lg px-4 py-3 transition duration-500 ease-in-out text-white bg-blue-500 hover:bg-blue-400 focus:outline-none"
                        >
                            <span class="font-bold">Send</span>
                            <SendIcon />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { ref } from "vue";
import { Link, router, usePage, Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/ChatLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

import SearchIcon from "@/Components/Icons/SearchIcon.vue";
import HeartIcon from "@/Components/Icons/HeartIcon.vue";
import BellIcon from "@/Components/Icons/BellIcon.vue";
import MicIcon from "@/Components/Icons/MicIcon.vue";
import PaperClipIcon from "@/Components/Icons/PaperClipIcon.vue";
import CameraIcon from "@/Components/Icons/CameraIcon.vue";
import EmojiIcon from "@/Components/Icons/EmojiIcon.vue";
import SendIcon from "@/Components/Icons/SendIcon.vue";
import ProfileIcon from "@/Components/Icons/ProfileIcon.vue";

const props = defineProps({
    user: Object,
    messages: Array,
    authUser: Object,
});

const user = props.user;
const messages = props.messages;
const authUser = usePage().props.auth.user;
</script>

<style scoped>
.scrollbar-w-2::-webkit-scrollbar {
    width: 0.25rem;
    height: 0.25rem;
}

.scrollbar-track-blue-lighter::-webkit-scrollbar-track {
    --bg-opacity: 1;
    background-color: #f7fafc;
    background-color: rgba(247, 250, 252, var(--bg-opacity));
}

.scrollbar-thumb-blue::-webkit-scrollbar-thumb {
    --bg-opacity: 1;
    background-color: #edf2f7;
    background-color: rgba(237, 242, 247, var(--bg-opacity));
}

.scrollbar-thumb-rounded::-webkit-scrollbar-thumb {
    border-radius: 0.25rem;
}
</style>
