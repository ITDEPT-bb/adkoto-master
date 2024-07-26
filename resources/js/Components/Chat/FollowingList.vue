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
</template>

<script setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    followings: {
        type: Array,
        required: true,
    },
    participants: {
        type: Array,
        required: true,
    },
});
</script>
