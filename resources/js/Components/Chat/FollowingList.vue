<template>
    <div class="h-full overflow-hidden w-full p-4 bg-white rounded-lg shadow">
        <div class="flex h-full flex-col sm:flex-row">
            <!-- Followings Column -->
            <div class="flex-1 overflow-y-auto pr-4">
                <div class="sticky top-0 bg-white shadow-sm z-10 p-2">
                    <h2 class="text-xl font-bold">Followings</h2>
                </div>
                <ul>
                    <li
                        v-for="following in followings"
                        :key="following.id"
                        class="mb-1 px-3 py-1 border-b border-gray-200"
                    >
                        <Link
                            :href="`/chat/conversation/adktu/${following.id}`"
                        >
                            <div
                                class="flex items-center space-x-4 transition-transform duration-300 ease-in-out transform hover:scale-105 hover:bg-gray-100 p-2 rounded-lg"
                            >
                                <img
                                    :src="following.avatar_url"
                                    alt="Avatar"
                                    class="w-10 h-10 rounded-full object-cover"
                                />
                                <div>
                                    <h3 class="text-lg font-medium">
                                        {{ following.name }}
                                        {{ following.surname }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        @{{ following.username }}
                                    </p>
                                </div>
                            </div>
                        </Link>
                    </li>
                </ul>
            </div>

            <!-- Group Column -->
            <div class="flex-1 overflow-y-auto pr-4">
                <div
                    class="sticky flex justify-between top-0 bg-white shadow-sm z-10 p-2"
                >
                    <h2 class="text-xl font-bold">My Group Chats</h2>
                    <button
                        @click="showNewGroupModal = true"
                        class="text-center items-center bg-red-500 hover:bg-red-600 text-white rounded py-1 px-2"
                    >
                        <!-- new group -->
                        <PlusIcon class="h-8 w-8" />
                    </button>
                </div>
                <ul>
                    <li
                        v-for="group in groupChats"
                        :key="group.id"
                        class="mb-1 px-3 py-1 border-b border-gray-200"
                    >
                        <Link :href="`/group-chat/${group.id}`">
                            <div
                                class="flex items-center space-x-4 transition-transform duration-300 ease-in-out transform hover:scale-105 hover:bg-gray-100 p-2 rounded-lg"
                            >
                                <img
                                    :src="group.photo"
                                    alt="Avatar"
                                    class="w-10 h-10 rounded-full object-cover"
                                />
                                <div>
                                    <h3 class="text-lg font-medium">
                                        {{ group.name }}
                                    </h3>
                                    <!-- <p class="text-sm text-gray-600 truncate">
                                        {{
                                            group.description ||
                                            "No description available"
                                        }}
                                    </p> -->
                                </div>
                            </div>
                        </Link>
                    </li>
                </ul>
            </div>

            <!-- Others Column -->
            <div class="flex-1 overflow-y-auto pl-4">
                <div class="sticky top-0 bg-white shadow-sm z-10 p-2">
                    <h2 class="text-xl font-bold">Others</h2>
                </div>
                <ul>
                    <li
                        v-for="participant in participants"
                        :key="participant.id"
                        class="mb-1 px-3 py-1 border-b border-gray-200"
                    >
                        <Link
                            :href="`/chat/conversation/adktu/${participant.id}`"
                        >
                            <div
                                class="flex items-center space-x-4 transition-transform duration-300 ease-in-out transform hover:scale-105 hover:bg-gray-100 p-2 rounded-lg"
                            >
                                <img
                                    :src="participant.avatar_url"
                                    alt="Avatar"
                                    class="w-10 h-10 rounded-full object-cover"
                                />
                                <div>
                                    <h3 class="text-lg font-medium">
                                        {{ participant.name }}
                                        {{ participant.surname }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        @{{ participant.username }}
                                    </p>
                                </div>
                            </div>
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <CreateGroupModal v-model="showNewGroupModal" @create="onGroupCreate" />
</template>

<script setup>
import { defineProps, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { PlusIcon } from "@heroicons/vue/24/solid";

import CreateGroupModal from "@/Components/GroupChat/CreateGroupModal.vue";

const props = defineProps({
    followings: {
        type: Array,
        required: true,
    },
    participants: {
        type: Array,
        required: true,
    },
    groupChats: {
        type: Array,
        required: true,
    },
});

const showNewGroupModal = ref(false);

// function onGroupCreate(group) {
//     props.groups.unshift(group);
// }
</script>
