<template>
	<Head title="Item" />

	<KalakalLayout>
		<div class="max-w-7xl mx-auto h-full overflow-y-auto p-4 scrollbar-thin">
			<AuctionMenu />
			<div class="px-4 mx-auto 2xl:px-0 my-4">
				<div class="lg:grid lg:grid-cols-3 lg:gap-8 xl:gap-10">
					<!-- Image Carousel -->
					<div class="relative">
						<div class="overflow-hidden rounded-t-lg">
							<div
								class="flex"
								:style="{
									transform: `translateX(-${currentIndex * 100}%)`,
									transition: 'transform 0.5s ease',
								}">
								<img
									v-for="(attachment, index) in item.attachments"
									:key="index"
									:src="attachment.image_path"
									alt="Auction Image"
									class="w-full h-full object-contain" />
							</div>
						</div>
						<button
							@click="prevImage"
							class="absolute top-1/2 lg:top-1/4 left-4 transform -translate-y-1/2 bg-white text-blue-500 font-bold p-2 rounded-full">
							&lt;
						</button>
						<button
							@click="nextImage"
							class="absolute top-1/2 lg:top-1/4 right-4 transform -translate-y-1/2 bg-white text-blue-500 font-bold p-2 rounded-full">
							&gt;
						</button>

						<!-- Manage Button (Edit/Delete) -->
						<div
							v-if="item.user.id === authUser.id"
							class="flex-2">
							<!-- <a
                                :href="
                                    route('kalakalkoto.edit', {
                                        id: item.id,
                                    })
                                "
                                class="bg-yellow-500 text-white py-1 px-2 rounded text-xs mr-2"
                            >
                                Edit
                            </a>
                            <button
                                @click="openDeleteModal"
                                class="bg-red-500 text-white py-1 px-2 rounded text-xs m-2"
                            >
                                Delete
                            </button> -->
						</div>
					</div>

					<!-- Item Details -->
					<div class="mt-6 sm:mt-8 lg:mt-0 p-4 lg:h-full overflow-hidden bg-white rounded">
						<h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
							{{ item.name }}
						</h1>

						<div class="mt-4 sm:items-center sm:gap- sm:flex-col">
							<p class="text-lg font-extrabold text-gray-900 sm:text-lg dark:text-white">
								Starting Price:
								{{ formatPrice(item.starting_price) }}
							</p>
						</div>
						<div class="mt-4 sm:items-center sm:gap- sm:flex-col">
							<p class="text-md font-extrabold text-gray-600 sm:text-md dark:text-white">
								Bid Increment:
								{{ formatPrice(item.bid_increment) }}
							</p>
						</div>

						<div v-if="item.user.id !== authUser.id">
							<button
								@click="placeBid(item.id)"
								:disabled="isLoadingBid"
								:class="[
									'group my-6 w-full sm:gap-4 sm:items-center justify-center sm:flex sm:my-4 rounded-md p-2',
									{
										'bg-blue-200 text-white cursor-not-allowed':
											isLoadingBid || isUserHighestBidder,
										'bg-blue-300 hover:bg-blue-500 transition ease-in-out delay-50 hover:-translate-y-1 hover:scale-105 duration-300 hover:text-white':
											!isLoadingBid && !isUserHighestBidder,
									},
								]">
								<div class="flex gap-3 items-center">
									<BankNoteIcon />
									<p class="font-bold">
										<span v-if="isLoadingBid">Placing Bid...</span>
										<span v-else>Place Bid</span>
									</p>
								</div>
							</button>
						</div>

						<div class="flex items-center my-2 dark:text-gray-300">
							<ClockIcon class="mr-2 dark:text-gray-400" />
							<p class="text-sm font-semibold dark:text-gray-400">
								Auction ends on:
								<span class="underline">{{ formattedDate }}</span>
							</p>
						</div>

						<hr class="my-6 md:my-8 border-gray-200 dark:border-gray-800" />

						<div class="flex items-center text-gray-700 my-2 dark:text-gray-300">
							<UserIcon class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" />
							<p class="text-sm font-semibold text-gray-600 dark:text-gray-400">
								{{ item.user.name }} {{ item.user.surname }}
							</p>
						</div>

						<div class="flex items-center text-gray-700 my-2 dark:text-gray-300">
							<MapPinIcon class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" />
							<p class="text-sm font-semibold text-gray-600 dark:text-gray-400">
								{{ item.location }}
							</p>
						</div>

						<p class="mb-6 text-gray-500 dark:text-gray-400">
							{{ item.description }}
						</p>
					</div>

					<div class="lg:h-full overflow-hidden">
						<BiddingList
							:items="bids"
							:highBid="highBid" />
					</div>
				</div>
			</div>
			<!-- Delete Modal -->
			<TransitionRoot
				as="template"
				:show="open">
				<Dialog
					class="relative z-10"
					@close="open = false">
					<TransitionChild
						as="template"
						enter="ease-out duration-300"
						enter-from="opacity-0"
						enter-to="opacity-100"
						leave="ease-in duration-200"
						leave-from="opacity-100"
						leave-to="opacity-0">
						<div class="fixed inset-0 bg-gray-600 bg-opacity-90 transition-opacity" />
					</TransitionChild>

					<div class="fixed inset-0 z-10 w-screen overflow-y-auto">
						<div
							class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
							<TransitionChild
								as="template"
								enter="ease-out duration-300"
								enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
								enter-to="opacity-100 translate-y-0 sm:scale-100"
								leave="ease-in duration-200"
								leave-from="opacity-100 translate-y-0 sm:scale-100"
								leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
								<DialogPanel
									class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
									<div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
										<div class="sm:flex sm:items-start">
											<div
												class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
												<ExclamationTriangleIcon
													class="h-6 w-6 text-red-600"
													aria-hidden="true" />
											</div>
											<div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
												<DialogTitle
													as="h3"
													class="text-base font-semibold leading-6 text-gray-900">
													Delete Listing
												</DialogTitle>
												<div class="mt-2">
													<p class="text-sm text-gray-500">
														Are you sure you want to delete this listing?
													</p>
												</div>
											</div>
										</div>
									</div>
									<div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
										<button
											type="button"
											:disabled="isLoading"
											:class="{
												'inline-flex w-full justify-center rounded-md bg-red-200 px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto cursor-not-allowed':
													isLoading,
												'inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto':
													!isLoading,
											}"
											@click="deleteAd(item.id)">
											Delete
										</button>
										<button
											type="button"
											class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
											@click="open = false"
											ref="cancelButtonRef">
											Cancel
										</button>
									</div>
								</DialogPanel>
							</TransitionChild>
						</div>
					</div>
				</Dialog>
			</TransitionRoot>
		</div>
	</KalakalLayout>

	<UpdateProfileReminder />
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { usePage, Head, Link } from "@inertiajs/vue3";
import KalakalLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import KalakalMenu from "@/Components/Kalakalkoto/KalakalMenu.vue";
import AuctionMenu from "@/Components/Auction/AuctionMenu.vue";
import axiosClient from "@/axiosClient.js";
import { useToast } from "vue-toastification";
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from "@headlessui/vue";
import { ExclamationTriangleIcon, CheckCircleIcon } from "@heroicons/vue/24/outline";

import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(utc);
dayjs.extend(relativeTime);

import BankNoteIcon from "@/Components/Icons/BankNoteIcon.vue";
import UserIcon from "@/Components/Icons/UserIcon.vue";
import MapPinIcon from "@/Components/Icons/MapPinIcon.vue";
import BiddingList from "@/Components/Auction/BiddingList.vue";
import ClockIcon from "@/Components/Icons/ClockIcon.vue";

const open = ref(false);
const openDeleteModal = () => {
	open.value = true;
};

const toast = useToast();
const { props } = usePage();
const item = ref(props.item);
const bids = ref(props.bids);
const highBid = ref(props.highBid);
const emit = defineEmits(["deleted", "sold"]);

const auctionEndDate = ref(props.item.auction_ends_at);

const formattedDate = computed(() => {
	return dayjs(auctionEndDate.value).format("MMMM D, YYYY h:mm A");
});

const remainingTime = computed(() => {
	return dayjs(auctionEndDate.value).fromNow();
});

const authUser = usePage().props.auth.user;

const formatPrice = (price) => {
	return new Intl.NumberFormat("en-PH", {
		style: "currency",
		currency: "PHP",
		minimumFractionDigits: 2,
	}).format(price);
};

const currentIndex = ref(0);
const nextImage = () => {
	if (item.value.attachments.length > 0) {
		if (currentIndex.value < item.value.attachments.length - 1) {
			currentIndex.value++;
		} else {
			currentIndex.value = 0;
		}
	}
};

const prevImage = () => {
	if (item.value.attachments.length > 0) {
		if (currentIndex.value > 0) {
			currentIndex.value--;
		} else {
			currentIndex.value = item.value.attachments.length - 1;
		}
	}
};

const isLoading = ref(false);
const deleteAd = async (id) => {
	isLoading.value = true;
	try {
		await axiosClient.delete(`/auction/${id}`);
		toast.success("Listing deleted successfully!");
		emit("deleted", id);
		isLoading.value = false;
		window.location.href = "/auction";
	} catch (error) {
		toast.error("Failed to delete the Listing.");
		console.error(error);
		isLoading.value = false;
	}
};

const isLoadingBid = ref(false);
const intervalId = ref(null);

const isUserHighestBidder = computed(() => {
	return highBid.value && highBid.value.user_id === authUser.id;
});

// const placeBid = async (itemId) => {
//     isLoadingBid.value = true;
//     try {
//         const response = await axiosClient.post(`/auction/bid/${itemId}`);
//         toast.success("Bid successfully placed");
//         isLoadingBid.value = false;
//         fetchLatestBids();
//     } catch (error) {
//         toast.error("Failed to place bid.");
//         isLoadingBid.value = false;
//     }
// };
const placeBid = async (itemId) => {
	if (isUserHighestBidder.value) {
		toast.error("You already have the highest bid.");
		return;
	}

	isLoadingBid.value = true;
	try {
		const response = await axiosClient.post(`/auction/bid/${itemId}`);
		toast.success("Bid successfully placed");
		fetchLatestBids();
	} catch (error) {
		toast.error("Failed to place bid.");
	} finally {
		isLoadingBid.value = false;
	}
};

const fetchLatestBids = async () => {
	try {
		const response = await axiosClient.get(`/auction/${item.value.id}/bids`);
		bids.value = response.data.bids;
		highBid.value = response.data.highBid;
	} catch (error) {
		console.error("Failed to fetch the latest bids:", error);
	}
};

onMounted(() => {
	intervalId.value = setInterval(() => {
		fetchLatestBids();
	}, 5000);

	fetchLatestBids();
});

onBeforeUnmount(() => {
	if (intervalId.value) {
		clearInterval(intervalId.value);
	}
});
</script>

<style scoped></style>
