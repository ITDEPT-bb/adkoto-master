<template>
    <div
        v-if="item"
        class="block bg-white dark:bg-slate-950 rounded-lg shadow-lg overflow-hidden transition-transform transform hover:scale-105"
    >
        <!-- <a :href="route('auction.show', { id: item.id })"> -->
        <Link :href="route('auction.watchStream')">
            <div
                class="w-full h-36 sm:h-48 md:h-64 bg-gray-200 dark:bg-slate-950 overflow-hidden"
            >
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
                <h2
                    class="text-xl font-semibold text-gray-900 dark:text-white truncate"
                >
                    {{ item.name }}
                </h2>
                <p class="text-base text-gray-700 dark:text-white truncate">
                    {{ item.description }}
                </p>
                <div class="mt-2">
                    <p
                        class="text-md font-semibold text-gray-900 dark:text-white"
                    >
                        Starting Bid: {{ formatPrice(item.starting_price) }}
                    </p>
                    <p
                        class="text-md font-semibold text-gray-900 dark:text-white"
                    >
                        Highest Bid:
                        {{
                            item.highest_bid
                                ? formatPrice(item.highest_bid)
                                : "No bids yet"
                        }}
                    </p>
                </div>
            </div>
        </Link>
    </div>

    <div v-else class="p-4 text-center text-gray-500">No items available</div>
</template>

<script setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";

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
