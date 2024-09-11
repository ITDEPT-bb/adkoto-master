<template>
    <div class="p-4 bg-white rounded">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
            Bidding List
        </h2>
        <div class="mt-4">
            <h1 class="text-lg font-bold px-2 text-center">Highest Bidder</h1>
            <div class="bg-green-100 dark:bg-gray-800 p-4 rounded-lg my-1">
                <div class="flex justify-between items-center">
                    <!-- Display bidder's name -->
                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                        {{ highBid.user.name }}
                    </p>
                    <!-- Display the bid amount -->
                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                        {{ formatPrice(highBid.bid_amount) }}
                    </p>
                </div>
                <!-- Display bid time -->
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <!-- {{ new Date(item.created_at).toLocaleString() }} -->
                    {{ dayjs(highBid.created_at).fromNow() }}
                </p>
            </div>
            <h1 class="text-lg font-bold px-2 text-center my-1">Bid History</h1>
            <ul class="space-y-2">
                <!-- Loop through bids and highlight the highest one -->
                <li
                    v-for="item in items"
                    :key="item.id"
                    :class="{
                        'bg-green-100 dark:bg-green-900':
                            item.bid_amount === highestBid,
                        'bg-gray-100 dark:bg-gray-800':
                            item.bid_amount !== highestBid,
                    }"
                    class="p-4 rounded-lg"
                >
                    <div class="flex justify-between items-center">
                        <!-- Display bidder's name -->
                        <p
                            class="text-sm font-bold text-gray-900 dark:text-white"
                        >
                            {{ item.user.name }} {{ item.user.surname }}
                        </p>
                        <!-- Display the bid amount -->
                        <p
                            class="text-sm font-bold text-gray-900 dark:text-white"
                        >
                            {{ formatPrice(item.bid_amount) }}
                        </p>
                    </div>
                    <!-- Display bid time -->
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ dayjs(item.created_at).fromNow() }}
                    </p>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(relativeTime);

const props = defineProps({
    items: {
        type: Object,
        required: true,
    },
    highBid: {
        type: Object,
        required: true,
    },
});

const highestBid = ref(0);

const formatPrice = (price) => {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
        minimumFractionDigits: 2,
    }).format(price);
};

function findHighestBid(items) {
    if (!items || items.length === 0) {
        return null;
    }

    let highestBid = items[0];

    items.forEach((item) => {
        if (item.bid > highestBid.bid) {
            highestBid = item;
        }
    });

    return highestBid;
}

findHighestBid();
</script>

<style scoped>
.bg-green-100 {
    background-color: #d4edda;
}
.bg-green-900 {
    background-color: #1c4532;
}
</style>
