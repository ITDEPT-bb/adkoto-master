<template>
    <div
        class="flex sm:items-center justify-between py-3 border-b-2 border-gray-200"
    >
        <div class="relative flex items-center space-x-4">
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
                <div class="text-1xl font-semibold mt-1 flex items-center">
                    <span class="text-gray-700 mr-3"
                        >{{ user.name }} {{ user.surname }}</span
                    >
                </div>
                <span class="text-sm text-gray-600">@{{ user.username }}</span>
            </div>
        </div>
        <div class="flex items-center space-x-2 px-2">
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
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import ProfileIcon from "@/Components/Icons/ProfileIcon.vue";

const props = defineProps({
    user: Object,
});

const user = props.user;
</script>
