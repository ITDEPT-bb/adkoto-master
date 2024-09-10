<template>
    <Head title="Kalakalkoto" />

    <KalakalLayout>
        <div class="max-w-7xl mx-auto h-full overflow-y-auto p-4">
            <PageSelector />
            <AuctionMenu />
            <div class="grid gap-4 lg:grid-cols-12 lg:gap-6">
                <div class="lg:col-span-9 bg-white p-6 rounded-lg shadow-sm">
                    <NormalItemList :items="normalBiddingItems" />
                </div>
                <div
                    class="lg:col-span-3 bg-white px-4 py-4 lg:px-6 lg:py-6 rounded-lg shadow-sm"
                >
                    <h2 class="text-2xl font-bold mb-4">Live Bidding</h2>
                    <NormalItemCard :item="liveBiddingItem" />
                </div>
            </div>
        </div>
    </KalakalLayout>

    <UpdateProfileReminder />
</template>

<script setup>
import { ref } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import KalakalLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

import AuctionMenu from "@/Components/Auction/AuctionMenu.vue";
import PageSelector from "@/Components/Kalakalkoto/PageSelector.vue";
import NormalItemList from "@/Components/Auction/NormalItemList.vue";
import NormalItemCard from "@/Components/Auction/NormalItemCard.vue";

const { props } = usePage();
const normalBiddingItems = ref(props.normalBiddingItems);
const liveBiddingItem = ref(props.liveBiddingItem || null);
</script>
