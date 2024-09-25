<template>
    <div class="flex items-center justify-center h-screen bg-gray-100">
        <div class="text-center bg-white p-8 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4">Checkout Canceled</h1>
            <p class="text-gray-700 mb-6">
                Your checkout process has been canceled. If this was a mistake,
                feel free to start again.
            </p>
            <Link :href="`/kalakalkoto/`">
                <div
                    class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition"
                >
                    Go Back to Home
                </div>
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<style scoped></style>
