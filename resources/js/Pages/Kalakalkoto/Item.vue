<script setup>
import { ref } from "vue";
import { usePage, Head, Link } from "@inertiajs/vue3";
import KalakalLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

const { props } = usePage();
const item = ref(props.item);
</script>

<template>
    <Head title="Item Detail" />

    <KalakalLayout>
        <div class="max-w-7xl overflow-y-auto h-full mx-auto p-4">
            <div class="bg-white dark:bg-slate-950 rounded-lg border dark:border-slate-900 p-6">
                <img :src="item.images[0]?.url || 'https://via.placeholder.com/150'"
                    :alt="item.name" class="w-full object-cover mb-4 rounded-lg shadow-lg">
                <h3 class="text-3xl font-semibold mb-2">{{ item.name }}</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4">{{ item.description }}</p>
                <div class="flex items-center justify-between">
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">₱{{ item.price }}</p>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Category: {{ item.category.name }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Posted by: {{ item.user.name }}</p>
            </div>
        </div>
    </KalakalLayout>
    <UpdateProfileReminder />
</template>

<style scoped></style>
