<template>
    <Head title="Kalakalkoto" />

    <KalakalLayout>
        <div class="max-w-7xl mx-auto h-full overflow-y-auto p-4">
            <PageSelector />
            <AuctionMenu />
            <div class="grid gap-4 lg:grid-cols-12 lg:gap-6">
                <div class="lg:col-span-9 bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-2xl font-bold mb-4">Normal Bidding</h2>
                    <NormalItemList :items="normalBiddingItems" />
                </div>
                <div
                    class="lg:col-span-3 bg-white px-4 py-4 lg:px-6 lg:py-6 rounded-lg shadow-sm"
                >
                    <h2 class="text-2xl font-bold mb-4">Live Bidding</h2>
                    <NormalItemCard :item="liveBiddingItem" />
                    <a
                        :href="route('auction.viewAllLive')"
                        class="font-bold w-full sm:gap-4 sm:items-center justify-center sm:flex sm:my-4 rounded-md p-2 bg-blue-300 hover:bg-blue-500 hover:text-white"
                    >
                        View All Live Bidding
                    </a>
                </div>
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
const normalBiddingItems = ref(props.normalBiddingItems);
const liveBiddingItem = ref(props.liveBiddingItem || null);
const intervalId = ref(null);

const fetchLatestBidsList = async () => {
    try {
        const response = await axiosClient.get(`/auction/bids/list`);
        normalBiddingItems.value = response.data.normalBiddingItems;
        liveBiddingItem.value = response.data.liveBiddingItem;
    } catch (error) {
        console.error("Failed to fetch the latest bids:", error);
    }
};

onMounted(() => {
    intervalId.value = setInterval(() => {
        fetchLatestBidsList();
    }, 5000);

    fetchLatestBidsList();
});

onBeforeUnmount(() => {
    if (intervalId.value) {
        clearInterval(intervalId.value);
    }
});
</script>
