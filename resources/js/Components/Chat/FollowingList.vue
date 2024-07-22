<template>
    <div class="h-full overflow-y-auto w-full p-4 bg-white rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Followings</h2>
        <ul>
            <li
                v-for="following in followings"
                :key="following.id"
                class="mb-1 px-3 py-1 border-b border-gray-200"
            >
                <Link :href="`/chat/conversation/adktu/${following.id}`">
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
                                {{ following.name }} {{ following.surname }}
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
</template>

<script setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    followings: {
        type: Array,
        required: true,
    },
});
</script>

<style scoped>
/* .following-list {
    max-height: 500px;
    overflow-y: auto;
  } */
</style>
