<template>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <!-- <p>
            Welcome back, <strong>{{ userName }}</strong
            >.
        </p>
        <p class="text-gray-500 text-sm">Last logged in: {{ memberSince }}</p> -->
        <p class="mt-4">
            Manage your ads or create new ones from your personalized dashboard.
        </p>
        <div class="mt-6 space-x-4">
            <a
                class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 hover:cursor-pointer"
            >
                Manage Ads
            </a>
            <a
                :href="route('adkoto.create')"
                class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 hover:cursor-pointer"
            >
                Create Ads
            </a>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

// const userName = ref("tianny");
// const memberSince = ref("2024/08/20 2:55 PM");
</script>

<style scoped></style>
