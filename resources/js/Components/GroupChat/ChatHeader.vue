<template>
    <!-- <div
        class="flex flex-col sm:flex-row sm:items-center justify-between py-3 px-4 rounded-lg border-b-2 border-gray-200 dark:border-none"
        style="background-color: #0076be"
    > -->
    <div
        class="flex sm:flex-row sm:items-center justify-between py-3 px-4 rounded-lg border-b-2 border-gray-200 dark:border-none"
        style="background-color: #0076be"
    >
        <div class="relative flex items-center space-x-4">
            <Link :href="route('chat.index')">
                <button
                    class="flex items-center justify-center w-6 h-6 p-1 rounded-full bg-gray-200 hover:bg-gray-300"
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
                <img
                    :src="group.photo"
                    alt=""
                    class="w-10 sm:w-12 h-10 sm:h-12 rounded-full"
                />
            </div>
            <div class="flex flex-col leading-tight">
                <div class="text-1xl font-semibold mt-1 flex items-center">
                    <span class="text-white mr-3 dark:text-white">{{
                        group.name
                    }}</span>
                </div>
                <!-- <span class="text-sm text-gray-600">{{
                    group.description
                }}</span> -->
            </div>
        </div>

        <div class="flex">
            <div class="flex items-center space-x-2 px-2">
                <button
                    @click="showUpdateModal = true"
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg border h-6 w-6 sm:h-10 sm:w-10 transition duration-500 ease-in-out text-white hover:text-black dark:text-white hover:bg-gray-300 focus:outline-none"
                >
                    <SettingsIcon />
                </button>
                <UpdateInfoModal
                    :isOpen="showUpdateModal"
                    :groupChat="group"
                    @close="showUpdateModal = false"
                />

                <button
                    class="inline-flex items-center justify-center rounded-lg border h-6 w-6 sm:h-10 sm:w-10 transition duration-500 ease-in-out text-white hover:text-black dark:text-white hover:bg-gray-300 focus:outline-none"
                    type="button"
                    @click="GroupMemberModal = true"
                >
                    <UserGroupIcon />
                </button>
                <GroupChatMemberModal
                    :isOpen="GroupMemberModal"
                    :group="group"
                    @close="GroupMemberModal = false"
                />

                <!-- Leave Button -->
                <button
                    class="inline-flex items-center justify-center rounded-lg border h-6 w-6 sm:h-10 sm:w-10 transition duration-500 ease-in-out text-white hover:text-black dark:text-white hover:bg-gray-300 focus:outline-none"
                    type="button"
                    @click="confirmLeaveGroup"
                >
                    <ArrowLeftStartOnRectangleIcon />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import axiosClient from "@/axiosClient.js";

import SettingsIcon from "../Icons/SettingsIcon.vue";
import UserGroupIcon from "../Kalakalkoto/UserGroupIcon.vue";
import { ArrowLeftStartOnRectangleIcon } from "@heroicons/vue/24/outline";

import UpdateInfoModal from "./UpdateInfoModal.vue";
import GroupChatMemberModal from "./GroupChatMemberModal.vue";

const props = defineProps({
    group: Object,
});

const user = props.user;

const GroupMemberModal = ref(false);
const showUpdateModal = ref(false);

const toast = useToast();

const confirmLeaveGroup = () => {
    if (confirm("Are you sure you want to leave this group?")) {
        leaveGroup();
    }
};

const leaveGroup = () => {
    router
        .delete(route("group-chats.leave", props.group), {
            preserveScroll: true,
        })
        // .then(() => {
        //     open.value = false;
        // })
        .catch((error) => {
            console.error("Failed to leave group:", error);
        });
};
</script>
