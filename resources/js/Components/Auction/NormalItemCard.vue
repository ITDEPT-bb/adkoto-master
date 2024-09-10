<template>
    <a
        :href="route('kalakalkoto.show', { id: item.id })"
        class="block bg-white rounded-lg shadow-lg overflow-hidden transition-transform transform hover:scale-105"
    >
        <div class="w-full h-48 bg-gray-200 overflow-hidden">
            <img
                :src="
                    item.attachments[0]?.image_path ||
                    'https://via.placeholder.com/400'
                "
                :alt="item.name"
                class="w-full h-full object-cover"
            />
        </div>

        <div class="p-4">
            <h2 class="text-xl font-semibold text-gray-900 truncate">
                {{ item.name }}
            </h2>
            <p class="text-base text-gray-700 truncate">
                {{ item.description }}
            </p>
            <div class="mt-2">
                <p class="text-md font-semibold text-gray-900">
                    Starting Bid: {{ formatPrice(item.starting_price) }}
                </p>
                <p class="text-md font-semibold text-gray-900">
                    Highest Bid: {{ formatPrice(item.current_bid) }}
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

<style scoped></style>
