<template>
	<Head title="Kalakalkoto" />

	<AuthenticatedLayout>
		<div class="h-full overflow-y-auto p-4 scrollbar-thin">
			<div class="max-w-7xl mx-auto">
				<PageSelector />
				<AuctionMenu />
			</div>

			<div
				class="bg-white p-3 my-2 block lg:hidden dark:bg-slate-950 rounded border dark:border-slate-900 dark:text-gray-100 h-full overflow-hidden">
				<Disclosure v-slot="{ open }">
					<DisclosureButton class="w-full">
						<div class="flex justify-between items-center">
							<h2 class="text-xl font-bold">Live Bidding</h2>
							<svg
								xmlns="http://www.w3.org/2000/svg"
								fill="none"
								viewBox="0 0 24 24"
								stroke-width="1.5"
								stroke="currentColor"
								class="w-6 h-6 transition-all"
								:class="open ? 'rotate-90 transform' : ''">
								<path
									stroke-linecap="round"
									stroke-linejoin="round"
									d="M8.25 4.5l7.5 7.5-7.5 7.5" />
							</svg>
						</div>
					</DisclosureButton>
					<DisclosurePanel>
						<div
							class="col-span-12 lg:col-span-3 bg-white px-4 py-4 lg:px-6 lg:py-6 rounded-lg shadow-sm flex flex-col justify-between">
							<NormalItemCard :item="liveBiddingItem" />
							<!-- <a
								:href="route('auction.viewAllLive')"
								class="mt-4 lg:mt-6 w-full text-center font-bold bg-blue-300 hover:bg-blue-500 hover:text-white p-3 rounded-md transition-all duration-300 ease-in-out">
								View All Live Bidding
							</a> -->
							<!-- <a
								:href="route('auction.watchStream')"
								class="mt-4 lg:mt-6 w-full text-center font-bold bg-blue-300 hover:bg-blue-500 hover:text-white p-3 rounded-md transition-all duration-300 ease-in-out">
								View Live Bidding
							</a> -->
						</div>
					</DisclosurePanel>
				</Disclosure>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6 max-w-7xl mx-auto">
				<div class="col-span-12 lg:col-span-9 bg-white p-6 rounded-lg shadow-sm">
					<h2 class="text-2xl font-bold mb-4">Normal Bidding</h2>
					<NormalItemList :items="normalBiddingItems" />
				</div>
				<div
					class="col-span-12 lg:col-span-3 bg-white px-4 py-4 lg:px-6 lg:py-6 rounded-lg shadow-sm hidden lg:block">
					<h2 class="text-2xl font-bold mb-4">Live Bidding</h2>
					<LiveItemCard :item="liveBiddingItem" />
					<!-- <a
						:href="route('auction.viewAllLive')"
						class="font-bold w-full sm:gap-4 sm:items-center justify-center sm:flex sm:my-4 rounded-md p-2 bg-blue-300 hover:bg-blue-500 hover:text-white">
						View All Live Bidding
					</a> -->
					<!-- <a
						:href="route('auction.watchStream')"
						class="font-bold w-full sm:gap-4 sm:items-center justify-center sm:flex sm:my-4 rounded-md p-2 bg-blue-300 hover:bg-blue-500 hover:text-white">
						View Live Bidding
					</a> -->
				</div>
			</div>
		</div>
	</AuthenticatedLayout>

	<UpdateProfileReminder />
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import axiosClient from "@/axiosClient.js";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

import AuctionMenu from "@/Components/Auction/AuctionMenu.vue";
import PageSelector from "@/Components/Kalakalkoto/PageSelector.vue";
import NormalItemList from "@/Components/Auction/NormalItemList.vue";
import NormalItemCard from "@/Components/Auction/NormalItemCard.vue";
import LiveItemCard from "@/Components/Auction/LiveItemCard.vue";

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
