<template>
    <div class="h-full overflow-hidden w-full p-4 bg-white rounded-lg shadow">
        <div class="flex h-full flex-col sm:flex-row">
            <!-- Buyers Messages Column -->
            <div class="flex-1 overflow-y-auto pr-4">
                <div class="sticky top-0 bg-white shadow-sm z-10 p-2">
                    <h2 class="text-xl font-bold">Messages</h2>
                </div>
                <ul>
                    <li
                        v-for="(
                            { participant, item_id }, index
                        ) in participants"
                        :key="index"
                        class="mb-1 px-3 py-1 border-b border-gray-200"
                    >
                        <!-- Adjust the Link to include both user ID and kalakalId -->
                        <Link
                            :href="`/kalakalkoto/chat/conversation/klklktu/${participant.id}/${item_id}`"
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
import { ref } from "vue";
import { usePage, Link } from "@inertiajs/vue3";

// Get props from Inertia
const { props } = usePage();
const participants = ref(props.participants);
</script>
