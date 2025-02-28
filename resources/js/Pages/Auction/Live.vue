<template>
	<Head title="Kalakalkoto" />

	<AuthenticatedLayout>
		<div class="max-w-7xl mx-auto h-full overflow-y-auto p-4 scrollbar-thin">
			<PageSelector />
			<AuctionMenu />
			<div class="bg-white p-6 rounded-lg shadow-sm">
				<h2 class="text-2xl font-bold mb-4">Live Bidding</h2>

				<!-- Check if liveBiddingItem has items -->
				<div v-if="liveBiddingItem && liveBiddingItem.length > 0">
					<LiveItemCard :items="liveBiddingItem" />
				</div>
				<div
					v-else
					class="text-gray-500">
					No live bidding available
				</div>
			</div>
		</div>
	</AuthenticatedLayout>

	<UpdateProfileReminder />
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import axiosClient from "@/axiosClient.js";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

import AuctionMenu from "@/Components/Auction/AuctionMenu.vue";
import PageSelector from "@/Components/Kalakalkoto/PageSelector.vue";
import LiveItemCard from "@/Components/Auction/LiveItemCard.vue";

const { props } = usePage();
const liveBiddingItem = ref(props.liveBiddingItem);
const intervalId = ref(null);

const fetchLatestBidsLive = async () => {
	try {
		const response = await axiosClient.get(`/auction/bids/live`);
		liveBiddingItem.value = response.data.liveBiddingItem;
	} catch (error) {
		console.error("Failed to fetch the latest bids:", error);
	}
};

onMounted(() => {
	intervalId.value = setInterval(() => {
		fetchLatestBidsLive();
	}, 20000);

	fetchLatestBidsLive();
});

onBeforeUnmount(() => {
	if (intervalId.value) {
		clearInterval(intervalId.value);
	}
});
</script>
