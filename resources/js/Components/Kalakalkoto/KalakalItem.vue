<template>
    <a
        :href="
            route('kalakalkoto.show', {
                id: item.id,
            })
        "
    >
        <div
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
                    <h2
                        class="text-lg font-semibold text-gray-800 dark:text-gray-200"
                    >
                        {{ item.title }}
                    </h2>
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
    </a>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    item: Object,
});

const formatPrice = (price) => {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
        minimumFractionDigits: 2,
    }).format(price);
};
</script>
