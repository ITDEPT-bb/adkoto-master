<template>
    <div>
        <!-- Auction Status Display -->
        <div
            v-if="winner"
            class="w-full bg-blue-50 border border-blue-200 p-4 mb-4 rounded-md"
        >
            <div class="flex items-center justify-center">
                <div class="bg-blue-100 p-2 rounded-full mr-3">
                    <span class="text-blue-600 text-xl">🏆</span>
                </div>
                <h2 class="text-blue-800 font-semibold text-lg">
                    Winner:
                    <span class="font-bold">{{ winner.name }}</span> with
                    {{ formatPrice(winner.amount) }}
                </h2>
            </div>
        </div>

        <div
            v-else
            class="w-full bg-gray-50 border border-gray-200 p-4 mb-4 rounded-md"
        >
            <div class="flex items-center justify-center mb-3">
                <div class="bg-gray-100 p-2 rounded-full mr-3">
                    <span class="text-gray-600 text-xl">⏱️</span>
                </div>
                <h2 class="text-gray-800 font-semibold text-lg">
                    Auction {{ auctionStatus }}
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Current Bid Section -->
                <div
                    class="bg-white p-3 rounded-lg border border-gray-100 shadow-sm"
                >
                    <div class="flex items-center mb-2">
                        <span class="text-green-600 text-lg mr-2">💰</span>
                        <h3 class="text-gray-700 font-medium">Current Bid</h3>
                    </div>
                    <p class="text-2xl font-bold text-green-600">
                        {{ formatPrice(computedCurrentBid) }}
                    </p>
                </div>

                <!-- Time Left Section -->
                <div
                    class="bg-white p-3 rounded-lg border border-gray-100 shadow-sm"
                >
                    <div class="flex items-center mb-2">
                        <span class="text-red-600 text-lg mr-2">⏰</span>
                        <h3 class="text-gray-700 font-medium">Time Left</h3>
                    </div>
                    <p class="text-2xl font-bold text-red-600">
                        {{ timeLeft }}s
                    </p>
                </div>
            </div>
        </div>

        <!-- Item Display -->
        <div
            class="max-w-7xl mx-auto h-full overflow-y-auto p-4 scrollbar-thin"
        >
            <div class="px-4 mx-auto 2xl:px-0 my-4">
                <div class="lg:grid lg:grid-cols-3 lg:gap-8 xl:gap-10">
                    <!-- Image Carousel -->
                    <div class="relative">
                        <div class="overflow-hidden rounded-t-lg">
                            <div
                                class="flex"
                                :style="{
                                    transform: `translateX(-${
                                        currentIndex * 100
                                    }%)`,
                                    transition: 'transform 0.5s ease',
                                }"
                            >
                                <img
                                    v-for="(
                                        attachment, index
                                    ) in item.attachments"
                                    :key="index"
                                    :src="attachment.image_path"
                                    alt="Auction Image"
                                    class="w-full h-full object-contain"
                                />
                            </div>
                        </div>
                        <button
                            @click="prevImage"
                            class="absolute top-1/2 lg:top-1/4 left-4 transform -translate-y-1/2 bg-white dark:bg-slate-950 dark:text-white text-blue-500 font-bold p-2 rounded-full"
                        >
                            &lt;
                        </button>
                        <button
                            @click="nextImage"
                            class="absolute top-1/2 lg:top-1/4 right-4 transform -translate-y-1/2 bg-white dark:bg-slate-950 dark:text-white text-blue-500 font-bold p-2 rounded-full"
                        >
                            &gt;
                        </button>
                    </div>

                    <!-- Item Details -->
                    <div
                        class="mt-6 sm:mt-8 lg:mt-0 p-4 lg:h-full overflow-hidden bg-white dark:bg-slate-950 rounded"
                    >
                        <h1
                            class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white"
                        >
                            {{ item.name }}
                        </h1>

                        <!-- <div class="mt-4 sm:items-center sm:gap- sm:flex-col">
                            <p
                                class="text-lg font-extrabold text-gray-900 sm:text-lg dark:text-white"
                            >
                                Starting Price:
                                {{ formatPrice(item.starting_price) }}
                            </p>
                        </div> -->
                        <div class="mt-4 sm:items-center sm:flex-col sm:gap-2">
                            <p
                                class="text-lg font-extrabold text-gray-900 sm:text-lg dark:text-white"
                            >
                                Starting Price:
                                {{ formatPrice(item.starting_price) }}
                            </p>
                            <p
                                class="text-md font-semibold text-gray-700 sm:text-md dark:text-gray-300"
                            >
                                Minimum Bid Increment:
                                {{
                                    bidIncrementResp > 0
                                        ? formatPrice(
                                              Math.ceil(bidIncrementResp)
                                          )
                                        : "Waiting..."
                                }}
                            </p>
                        </div>

                        <!-- Bid Input Section -->
                        <div v-if="item.user.id !== authUser.id">
                            <input
                                v-model.number="bidAmount"
                                type="number"
                                :min="minAllowedBid"
                                :disabled="
                                    bidIncrementResp === 0 || isLoadingBid
                                "
                                placeholder="Enter your bid"
                                class="border p-2 rounded w-full mb-3"
                            />
                            <p
                                v-if="bidAmount && bidAmount <= minAllowedBid"
                                class="text-red-500 text-sm mb-2"
                            >
                                Your bid must be higher than
                                {{ formatPrice(minAllowedBid) }}.
                            </p>
                            <button
                                @click="placeBid(item.id)"
                                :disabled="
                                    !isBidValid ||
                                    isLoadingBid ||
                                    bidIncrementResp === 0
                                "
                                :class="[
                                    'group my-6 w-full sm:gap-4 sm:items-center justify-center sm:flex sm:my-4 rounded-md p-2',
                                    {
                                        'bg-blue-200 text-white cursor-not-allowed':
                                            !isBidValid || isLoadingBid,
                                        'bg-blue-300 hover:bg-blue-500 transition ease-in-out delay-50 hover:-translate-y-1 hover:scale-105 duration-300 hover:text-white':
                                            isBidValid && !isLoadingBid,
                                    },
                                ]"
                            >
                                <div class="flex gap-3 items-center">
                                    <BankNoteIcon />
                                    <p class="font-bold">
                                        <span v-if="isLoadingBid"
                                            >Placing Bid...</span
                                        >
                                        <span v-else-if="bidIncrementResp === 0"
                                            >Waiting to start...</span
                                        >
                                        <span v-else>Place Bid</span>
                                    </p>
                                </div>
                            </button>
                        </div>

                        <hr
                            class="my-6 md:my-8 border-gray-200 dark:border-gray-800"
                        />

                        <!-- Seller Information -->
                        <div
                            class="flex items-center text-gray-700 my-2 dark:text-gray-300"
                        >
                            <UserIcon
                                class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400"
                            />
                            <p
                                class="text-sm font-semibold text-gray-600 dark:text-gray-400"
                            >
                                {{ item.user.name }} {{ item.user.surname }}
                            </p>
                        </div>

                        <!-- Location Information -->
                        <div
                            class="flex items-center text-gray-700 my-2 dark:text-gray-300"
                        >
                            <MapPinIcon
                                class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400"
                            />
                            <p
                                class="text-sm font-semibold text-gray-600 dark:text-gray-400"
                            >
                                {{ item.location }}
                            </p>
                        </div>

                        <!-- Item Description -->
                        <p class="mb-6 text-gray-500 dark:text-gray-400">
                            {{ item.description }}
                        </p>
                    </div>

                    <!-- Bidding History -->
                    <div class="lg:h-screen overflow-hidden">
                        <BiddingList :items="bids" :highBid="highBid" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import axiosClient from "@/axiosClient.js";
import { useToast } from "vue-toastification";

import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(utc);
dayjs.extend(relativeTime);

import BankNoteIcon from "@/Components/Icons/BankNoteIcon.vue";
import UserIcon from "@/Components/Icons/UserIcon.vue";
import MapPinIcon from "@/Components/Icons/MapPinIcon.vue";
import BiddingList from "@/Components/Auction/BiddingList.vue";

// Props and Emits
const props = defineProps({
    item: { type: Object, required: true },
    bids: { type: Array, required: true },
    highBid: { type: Object, default: null },
    user: { type: Object, default: null },
    walletBalance: { type: Number, default: 0 },
});

const emit = defineEmits(["deleted", "sold"]);

// Reactive State
const toast = useToast();

const item = ref(props.item);
const increment = ref(props.item.bid_increment);
const bids = ref(props.bids);
const highBid = ref(props.highBid);

const computedCurrentBid = computed(() => {
    if (props.highBid) {
        return props.highBid.amount;
    }
    if (props.bids && props.bids.length > 0) {
        return Math.max(...props.bids.map((bid) => bid.amount));
    }
    return props.item.starting_price ?? 0;
});

const currentBid = ref(computedCurrentBid.value);

const auctionStatus = ref("Starting...");
const isLoadingBid = ref(false);
const bidAmount = ref(null);
const currentIndex = ref(0);
const timeLeft = ref(0);
const timerInterval = ref(null);
const winner = ref(null);

const bidIncrementResp = ref(0);

// User Information
const authUser = usePage().props.auth.user;

// Computed Properties
const isUserHighestBidder = computed(() => {
    return highBid.value && highBid.value.user_id === authUser.id;
});

const minAllowedBid = computed(() => {
    const basePrice = highBid.value
        ? highBid.value.bid_amount
        : item.value.starting_price;
    return Math.ceil(basePrice) + Math.ceil(item.value.bid_increment);
});

const isBidValid = computed(() => {
    return (
        bidAmount.value &&
        bidAmount.value > minAllowedBid.value &&
        !isUserHighestBidder.value
    );
});

// Formatting Functions
// const formatPrice = (price) => {
//     return new Intl.NumberFormat("en-PH", {
//         style: "currency",
//         currency: "PHP",
//         minimumFractionDigits: 2,
//     }).format(price);
// };
const formatPrice = (price) => {
    const num = Number(price);
    if (isNaN(num)) return "₱0.00"; // fallback
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
        minimumFractionDigits: 2,
    }).format(num);
};

// Image Carousel Methods
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

// Bid Management Methods
const placeBid = async (itemId) => {
    if (!bidAmount.value || bidAmount.value <= 0) {
        toast.error("Please enter a valid bid amount.");
        return;
    }

    if (isUserHighestBidder.value) {
        toast.error("You already have the highest bid.");
        return;
    }

    if (!isBidValid.value) {
        toast.error(
            `Your bid must be higher than ₱${formatPrice(minAllowedBid.value)}.`
        );
        return;
    }

    isLoadingBid.value = true;
    try {
        await axiosClient.post(`/auction/bid/${itemId}`, {
            bid_amount: bidAmount.value,
        });
        toast.success("Bid successfully placed");
        bidAmount.value = null;
        fetchLatestBids();
    } catch (error) {
        if (error.response && error.response.data.error) {
            toast.error(error.response.data.error);
        } else {
            toast.error("Failed to place bid.");
        }
    } finally {
        isLoadingBid.value = false;
    }
};

const fetchLatestBids = async () => {
    try {
        const response = await axiosClient.get(
            `/auction/${item.value.id}/bids`
        );
        bids.value = response.data.bids;
        highBid.value = response.data.highBid;
    } catch (error) {
        console.error("Failed to fetch the latest bids:", error);
    }
};

// Timer Methods
function startTimer(endTime) {
    auctionStatus.value = "Ongoing";
    clearInterval(timerInterval.value);
    timerInterval.value = setInterval(() => {
        const diff = endTime - Math.floor(Date.now() / 1000);
        timeLeft.value = diff > 0 ? diff : 0;
        if (timeLeft.value <= 0) {
            clearInterval(timerInterval.value);
            showWinner();
        }
    }, 1000);
}

function showWinner() {
    auctionStatus.value = "Starting...";
    bidIncrementResp.value = 0;
    bidAmount.value = 0;
    if (bids.value.length > 0) {
        const lastBid = bids.value[0];
        winner.value = {
            name: lastBid.user.name,
            amount: lastBid.bid_amount,
        };
        toast.success(`Winner is ${lastBid.user.name}`);
    } else {
        winner.value = null;
        toast.info("No bids were placed for this item.");
    }
}

// Watchers
watch(
    () => props.item,
    (newItem) => {
        if (newItem) {
            item.value = newItem;
            fetchLatestBids();
            currentIndex.value = 0;
            bidAmount.value = null;
        }
    },
    { immediate: true }
);

// Lifecycle Hooks
onMounted(() => {
    window.Echo.join(`auction.${item.value.id}`).listen("BidPlaced", (e) => {
        currentBid.value = e.bid.amount;
        startTimer(e.endTime);
        fetchLatestBids();
        if (e.bid.user.id !== authUser.id) {
            toast.warning(
                `${e.bid.user.name} placed a bid of ${formatPrice(
                    e.bid.amount
                )}`
            );
        }
    });

    window.Echo.join("auction").listen("AuctionStarted", (e) => {
        toast.success("Bidding is now Open!");
        startTimer(e.endTime);
        console.log(e);
        bidIncrementResp.value = e.bidIncrement;
    });

    fetchLatestBids();
});
</script>

<style scoped></style>
