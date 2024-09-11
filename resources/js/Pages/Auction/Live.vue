<template>
    <Head title="Kalakalkoto" />

    <KalakalLayout>
        <div class="max-w-7xl mx-auto h-full overflow-y-auto p-4">
            <PageSelector />
            <AuctionMenu />
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-2xl font-bold mb-4">Live Bidding</h2>
                <NormalItemList :items="liveBiddingItem" />
            </div>
        </div>
    </KalakalLayout>

    <UpdateProfileReminder />
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import axiosClient from "@/axiosClient.js";

import KalakalLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

import AuctionMenu from "@/Components/Auction/AuctionMenu.vue";
import PageSelector from "@/Components/Kalakalkoto/PageSelector.vue";
import NormalItemList from "@/Components/Auction/NormalItemList.vue";
import NormalItemCard from "@/Components/Auction/NormalItemCard.vue";

const { props } = usePage();
const liveBiddingItem = ref(props.liveBiddingItem);
const intervalId = ref(null);

// const fetchLatestBidsList = async () => {
//     try {
//         const response = await axiosClient.get(`/auction/bids/list`);
//         liveBiddingItem.value = response.data.liveBiddingItem;
//     } catch (error) {
//         console.error("Failed to fetch the latest bids:", error);
//     }
// };

// onMounted(() => {
//     intervalId.value = setInterval(() => {
//         fetchLatestBidsList();
//     }, 5000);

//     fetchLatestBidsList();
// });

// onBeforeUnmount(() => {
//     if (intervalId.value) {
//         clearInterval(intervalId.value);
//     }
// });
</script>
