<script setup>
import { ref } from 'vue';
import { usePage, Link, Head } from '@inertiajs/vue3';
import KalakalLayout from "@/Layouts/KalakalLayout.vue";
import Sidebar from '@/Components/Kalakalkoto/Sidebar.vue';
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

const { props } = usePage();
const categories = ref(props.categories || []);
</script>

<template>
    <Head title="Kalakalkoto" />

    <KalakalLayout>
        <Sidebar :categories="categories" />

        <div class="p-4 h-full overflow-y-auto sm:ml-64">
            <div class="p-4 h-full overflow-y-auto rounded-lg dark:border-gray-700">
                <div class="grid lg:grid-cols-3 gap-4 mb-4">
                    <Link :href="route('adkoto')">
                        <div class="flex flex-col items-start p-4 max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                            <img src="https://via.placeholder.com/150" alt="Product Image" class="w-full h-40 object-cover rounded-t-lg" />
                            <div class="p-4 flex flex-col items-start space-y-2">
                                <div class="flex flex-col justify-between w-full">
                                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Product Name</h2>
                                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">$Price</p>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Location</p>
                            </div>
                        </div>
                    </Link>

                    <Link :href="route('adkoto')">
                        <div class="flex flex-col items-start p-4 max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                            <img src="https://via.placeholder.com/150" alt="Product Image" class="w-full h-40 object-cover rounded-t-lg" />
                            <div class="p-4 flex flex-col items-start space-y-2">
                                <div class="flex flex-col justify-between w-full">
                                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Product Name</h2>
                                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">$Price</p>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Location</p>
                            </div>
                        </div>
                    </Link>

                    <Link :href="route('adkoto')">
                        <div class="flex flex-col items-start p-4 max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                            <img src="https://via.placeholder.com/150" alt="Product Image" class="w-full h-40 object-cover rounded-t-lg" />
                            <div class="p-4 flex flex-col items-start space-y-2">
                                <div class="flex flex-col justify-between w-full">
                                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Product Name</h2>
                                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">$Price</p>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Location</p>
                            </div>
                        </div>
                    </Link>

                </div>
            </div>
        </div>
    </KalakalLayout>
    <UpdateProfileReminder />
</template>

<style scoped></style>
