<template>
    <Head title="Live Auction" />

    <AuthenticatedLayout>
        <div
            class="max-w-7xl mx-auto h-full overflow-y-auto p-4 scrollbar-thin"
        >
            <PageSelector />
            <AuctionMenu />

            <div
                class="bg-white dark:bg-slate-950 flex flex-col gap-4 p-6 rounded-lg shadow-sm"
            >
                <!-- Host Controls -->
                <div
                    v-if="authUser.is_filament_admin || isActiveSeller"
                    class="bg-white p-4 rounded mb-4 flex justify-between items-center"
                >
                    <div class="flex flex-col gap-2">
                        <h3 class="font-semibold mb-2">Host Controls</h3>
                        <div class="flex gap-2">
                            <input
                                type="number"
                                v-model="duration"
                                placeholder="Duration (seconds)"
                                class="border rounded px-2 py-1 w-28"
                            />
                            <!-- Increment Input -->
                            <input
                                type="number"
                                v-model.number="increment"
                                placeholder="Bid Increment (min is 10% of the item price)"
                                class="border rounded px-2 py-1 w-28"
                            />

                            <!-- Display formatted value -->
                            <p class="text-sm text-gray-500 mt-1">
                                Current Increment:
                                {{ formatPrice(Math.round(increment)) }}
                            </p>
                            <button
                                @click="startAuction"
                                :disabled="isLoading"
                                :class="[
                                    'px-4 py-2 text-white rounded transition-all duration-300',
                                    {
                                        'bg-green-200 cursor-not-allowed':
                                            isLoading,
                                        'bg-green-600 hover:bg-green-700':
                                            !isLoading,
                                    },
                                ]"
                            >
                                Start Auction
                            </button>
                            <ItemControllerPanel />
                        </div>
                    </div>

                    <div v-if="authUser.is_filament_admin" class="flex gap-2">
                        <!-- <AuctionSellerControlPanel /> -->
                        <ManageSellers />
                    </div>
                </div>

                <AgoraAuctionLive :appId="props.appId" />

                <!-- Show active item -->
                <ShowWindowLive
                    v-if="item && !noActiveBidding"
                    :item="item"
                    :highBid="highBid"
                    :bids="bids"
                    :user="user"
                    :walletBalance="walletBalance"
                />

                <div v-else class="text-center text-gray-500">
                    <p>No active bidding yet.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <UpdateProfileReminder />

    <!-- <div
        v-if="walletBalance < 1000 && !authUser.is_filament_admin"
        class="fixed inset-0 z-20 flex items-center justify-center bg-black bg-opacity-70"
    >
        <div
            class="bg-white dark:bg-slate-950 p-6 rounded-lg shadow-lg text-center max-w-md mx-auto"
        >
            <h2 class="text-xl font-bold text-red-600 mb-4">
                Wallet Balance Too Low
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-6">
                You need at least {{ formatPrice(1000) }} in your wallet to join
                the live auction.
                <br />
                Please recharge your wallet to continue.
            </p>
            <button
                @click="openModalRecharge"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
            >
                Recharge Wallet
            </button>
        </div>
    </div> -->

    <!-- Recharge Modal -->
    <RechargeModal
        :isOpen="isModalOpen"
        @close="closeModalRecharge"
        @recharge="handleRecharge"
    />
</template>

<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { ref, defineProps, onMounted, onUnmounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuctionLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import AuctionMenu from "@/Components/Auction/AuctionMenu.vue";
import PageSelector from "@/Components/Kalakalkoto/PageSelector.vue";
import ShowWindowLive from "@/Components/Auction/ShowWindowLive.vue";
import axios from "axios";
import AgoraAuctionLive from "@/Components/Auction/AgoraAuctionLive.vue";
import ItemControllerPanel from "@/Components/Auction/ItemControllerPanel.vue";

import { useToast } from "vue-toastification";
import RechargeModal from "@/Components/Auction/RechargeModal.vue";
import AuctionSellerControlPanel from "@/Components/Auction/AuctionSellerControlPanel.vue";
import ManageSellers from "@/Components/Auction/ManageSellers.vue";

const toast = useToast();

const { props } = usePage();
const authUser = usePage().props.auth.user;

const isActiveSeller = usePage().props.is_active_seller;

const item = ref(props.item);
const highBid = ref(props.highBid);
const bids = ref(props.bids);
const user = ref(props.user);
// const walletBalance = ref(props.walletBalance);
const walletBalance = ref(Number(props.walletBalance) || 0);

const noActiveBidding = ref(props.noActiveBidding);
const duration = ref(60);
const increment = ref(0);

const isLoading = ref(false);

const isModalOpen = ref(false);

function openModalRecharge() {
    isModalOpen.value = true;
}

function closeModalRecharge() {
    isModalOpen.value = false;
}

const formatPrice = (price) => {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
        minimumFractionDigits: 2,
    }).format(price || 0);
};

const fetchShowWindowData = async () => {
    try {
        const response = await axios.get("/auction/stream/bidlist");
        const data = response.data;

        increment.value = data.item.bid_increment;

        // Always update the data when we receive new data
        item.value = data.item;
        highBid.value = data.highBid;
        bids.value = data.bids;
        user.value = data.user;
        // walletBalance.value = data.walletBalance;
        // walletBalance.value = data.walletBalance ?? walletBalance.value;
        walletBalance.value = Number(data.walletBalance) || 0;
        noActiveBidding.value = data.noActiveBidding;
    } catch (error) {
        // Clear data on error
        item.value = null;
        highBid.value = null;
        bids.value = [];
        user.value = null;
        walletBalance.value = 0;
        noActiveBidding.value = true;
    }
};

const startAuction = () => {
    if (!item.value?.id) {
        console.error("No item ID found to start auction");
        return;
    }

    isLoading.value = true;

    axios
        .post(route("auction.start", { item: item.value.id }), {
            duration: duration.value,
            increment: increment.value,
        })
        .then((response) => {
            toast.success("Auction started successfully!");
        })
        .catch((error) => {
            if (error.response && error.response.data.message) {
                toast.error(error.response.data.message);
            } else {
                toast.error("Something went wrong! Please try again.");
            }
        })
        .finally(() => {
            isLoading.value = false;
        });
};

onMounted(() => {
    fetchShowWindowData();

    // Listen for auction item toggling
    window.Echo.channel("auction-items").listen(".AuctionItemToggled", (e) => {
        // Force refresh when any item is toggled
        fetchShowWindowData();
    });

    // Also listen for specific auction events
    window.Echo.channel("auction").listen("AuctionStarted", (e) => {
        console.log("Auction started event received");
        // Refresh data when auction starts
        fetchShowWindowData();
    });
});

onUnmounted(() => {
    // Clean up Echo listeners if needed
});
</script>
