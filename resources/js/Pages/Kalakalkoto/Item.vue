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
            <div class="flex flex-col md:flex-row bg-white dark:bg-slate-950 rounded-lg border dark:border-slate-900 p-6">
                <!-- Images Section -->
                <div class="md:w-1/2">
                    <div class="flex space-x-4 mb-4">
                        <!-- Loop through images -->
                        <template v-for="(image, index) in item.images" :key="index">
                            <img :src="`/storage/${image.path}`" :alt="item.name" class="w-32 h-32 object-cover rounded-lg shadow-lg">
                        </template>
                    </div>
                </div>

                <!-- Item Information Section -->
                <div class="md:w-1/2 md:pl-4">
                    <h3 class="text-3xl font-semibold mb-2">{{ item.name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ item.description }}</p>
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">₱{{ item.price }}</p>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Category: {{ item.category.name }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Posted by: {{ item.user.name }}</p>
                </div>
            </div>
        </div>
    </KalakalLayout>
    <UpdateProfileReminder />
</template>

<style scoped>
/* Add any additional styling as needed */
</style>
