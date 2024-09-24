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
                <img
                    :src="group.photo"
                    alt=""
                    class="w-10 sm:w-12 h-10 sm:h-12 rounded-full"
                />
            </div>
            <div class="flex flex-col leading-tight">
                <div class="text-1xl font-semibold mt-1 flex items-center">
                    <span class="text-gray-700 mr-3">{{ group.name }}</span>
                </div>
                <span class="text-sm text-gray-600">{{
                    group.description
                }}</span>
            </div>
        </div>
        <!-- <div class="flex items-center space-x-2 px-2">
            <Link :href="route('profile', user.username)">
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none"
                >
                    <ProfileIcon />
                </button>
            </Link>
        </div> -->

        <div class="text-center">
            <button
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                type="button"
                data-drawer-target="drawer-navigation"
                data-drawer-show="drawer-navigation"
                aria-controls="drawer-navigation"
            >
                View Members
            </button>
        </div>

        <div
            id="drawer-navigation"
            class="fixed top-0 left-0 z-40 w-64 h-screen p-4 overflow-y-auto bg-slate-300 dark:bg-gray-800 transition-transform -translate-x-full"
            tabindex="-1"
            aria-labelledby="drawer-navigation-label"
        >
            <h5
                id="drawer-navigation-label"
                class="text-base font-semibold text-gray-500 uppercase dark:text-gray-400"
            >
                Participants
            </h5>
            <button
                @click="addMemberModal = true"
                data-drawer-hide="drawer-navigation"
                aria-controls="drawer-navigation"
                class="py-2.5 px-5 w-full my-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
            >
                Add Member
            </button>
            <AddMemberModal
                :isOpen="addMemberModal"
                :groupId="group.id"
                @close="addMemberModal = false"
            />

            <button
                type="button"
                data-drawer-hide="drawer-navigation"
                aria-controls="drawer-navigation"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 end-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
            >
                <svg
                    aria-hidden="true"
                    class="w-5 h-5"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                    ></path>
                </svg>
                <span class="sr-only">Close menu</span>
            </button>
            <div class="py-4 overflow-y-auto">
                <ul
                    v-for="member in group.participants"
                    :key="member.id"
                    class="space-y-2 font-medium"
                >
                    <li>
                        <Link
                            :href="route('profile', member.username)"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                        >
                            <!-- <svg
                                class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 22 21"
                            >
                                <path
                                    d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"
                                />
                                <path
                                    d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"
                                />
                            </svg> -->
                            <img
                                :src="member.avatar_url"
                                alt="Avatar"
                                class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white rounded-full object-cover"
                            />
                            <span class="ms-3"
                                >{{ member.name }} {{ member.surname }}</span
                            >
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import ProfileIcon from "@/Components/Icons/ProfileIcon.vue";

import AddMemberModal from "@/Components/GroupChat/AddMemberModal.vue";

const props = defineProps({
    group: Object,
});

const user = props.user;

const addMemberModal = ref(false);
</script>
