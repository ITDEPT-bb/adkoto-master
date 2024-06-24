<template>
    <div class="bg-gray-white px-4 py-2 sticky top-0 z-10">
        <div
            class="bg-white rounded-lg shadow-md mx-auto px-4 py-1 flex items-center space-x-4"
        >
            <img
                class="w-10 h-10 rounded-full object-cover"
                :src="user.avatar_url"
                alt="User Image"
            />
            <div class="flex flex-col">
                <h2 class="text-xl font-bold">
                    {{ user.name }} {{ user.surname }}
                </h2>
                <span class="text-sm text-gray-600">@{{ user.username }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    user: Object,
});
</script>
