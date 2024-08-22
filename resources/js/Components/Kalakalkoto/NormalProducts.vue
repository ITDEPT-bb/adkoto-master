<template>
    <div class="lg:col-span-9">
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">
            All Products
        </h2>
        <div class="grid lg:grid-cols-3 gap-4">
            <div
                v-for="item in items"
                :key="item.id"
                class="flex flex-col items-start p-4 max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg"
            >
                <div class="w-full h-40 overflow-hidden rounded-t-lg">
                    <img
                        :src="
                            item.attachments[0]?.image_path ||
                            'https://via.placeholder.com/100'
                        "
                        :alt="item.name"
                        class="w-full h-full object-cover rounded-t-lg"
                    />
                </div>
                <div class="p-4 flex flex-col items-start space-y-2">
                    <div class="flex flex-col justify-between w-full">
                        <h3
                            class="text-lg font-semibold text-gray-800 dark:text-gray-200"
                        >
                            {{ item.title }}
                        </h3>
                        <p
                            class="text-lg font-bold text-gray-900 dark:text-gray-100"
                        >
                            {{ formatPrice(item.price) }}
                        </p>
                        <p class="text-md text-gray-900 dark:text-gray-100">
                            {{ item.description }}
                        </p>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Location: {{ item.location }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
});

const formatPrice = (price) => {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
        minimumFractionDigits: 2,
    }).format(price);
};
</script>
