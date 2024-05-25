<script setup>
import KalakalLayout from '@/Layouts/KalakalLayout.vue';
</script>

<template>

    <Head title="Gadget Set" />

    <KalakalLayout>

        <div class="max-w-7xl overflow-y-auto h-full mx-auto p-4">
            <div class="bg-white dark:bg-slate-950 rounded-lg border dark:border-slate-900 p-6">
                <img src="https://fastly.picsum.photos/id/26/4209/2769.jpg?hmac=vcInmowFvPCyKGtV7Vfh7zWcA_Z0kStrPDW3ppP0iGI"
                    alt="Gadget Set" class="w-full object-cover mb-4 rounded-lg shadow-lg">
                <h3 class="text-3xl font-semibold mb-2">Gadget Set</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4">All in one gadget set for your everyday needs.</p>
                <div class="flex items-center justify-between">
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">₱30000.99</p>
                    <!-- <button class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">Add to Cart</button> -->
                </div>
            </div>
        </div>
    </KalakalLayout>
</template>

<script>
import { Head } from '@inertiajs/vue3';

export default {
    components: {
        Head
    }
};
</script>

<style scoped>
/* Add any additional styles here */
</style>
