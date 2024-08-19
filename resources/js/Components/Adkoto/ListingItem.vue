<template>
    <div class="border-b py-4">
        <div class="flex items-start space-x-4">
            <img
                :src="
                    advertisement.image_path ||
                    'https://via.placeholder.com/100'
                "
                alt="Listing Image"
                class="w-20 h-20 object-cover rounded"
            />
            <div>
                <h3 class="text-lg font-semibold">{{ advertisement.title }}</h3>
                <p class="text-sm text-gray-600">
                    {{ advertisement.description }}
                </p>
                <div
                    class="text-sm text-gray-500 flex items-center space-x-2 mt-2"
                >
                    <span>{{ advertisement.category }}</span>
                    <span>{{ advertisement.date }}</span>
                    <!-- <span>394 total views, 1 today</span> -->
                </div>
            </div>
            <div class="ml-auto">
                <span class="bg-red-500 text-white py-1 px-2 rounded"
                    >₱{{ advertisement.price }}</span
                >
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    advertisement: Object,
});
</script>
