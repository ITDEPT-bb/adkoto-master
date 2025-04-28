<template>
    <div class="bg-white dark:bg-slate-950 p-6 rounded-lg shadow">
        <div class="flex flex-col gap-2">
            <p class="dark:text-white">
                Welcome back,
                <strong>{{ authUser.name }} {{ authUser.surname }}</strong
                >.
            </p>
            <p class="text-gray-500 dark:text-white text-sm">
                Member Since: {{ formatDate(authUser.created_at) }}
            </p>
        </div>
        <p class="mt-4 dark:text-white">
            Manage your ads or create new ones from your personalized dashboard.
        </p>
        <div class="mt-6 space-x-4">
            <Link
                :href="route('adkoto.showUserAds')"
                class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 hover:cursor-pointer"
            >
                Manage Ads
            </Link>
            <Link
                :href="route('adkoto.create')"
                class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 hover:cursor-pointer"
            >
                Create Ads
            </Link>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import dayjs from "dayjs";

const authUser = usePage().props.auth.user;
const formatDate = (date) => {
    return dayjs(date).format("MMMM D, YYYY");
};
</script>

<style scoped></style>
