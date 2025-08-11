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
                <div
                    v-if="authUser.is_filament_admin"
                    class="bg-white p-4 rounded mb-4"
                >
                    <h3 class="font-semibold mb-2">Host Controls</h3>
                    <div class="flex gap-2">
                        <input
                            type="number"
                            v-model="duration"
                            placeholder="Duration (seconds)"
                            class="border rounded px-2 py-1 w-28"
                        />
                        <button
                            @click="startAuction"
                            class="px-4 py-2 bg-green-600 text-white rounded"
                        >
                            Start Auction
                        </button>
                        <ItemControllerPanel />
                    </div>
                </div>
                <!-- <YouTubeLiveStream :channelId="youtubeChannelId" /> -->
                <AgoraAuctionLive :appId="props.appId" />

                <ShowWindowLive
                    v-if="!noActiveBidding"
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
</template>

<script setup>
import YouTubeLiveStream from "@/Components/Auction/YouTubeLiveStream.vue";
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

const { props } = usePage();

const authUser = usePage().props.auth.user;

const updatedItem = ref(props.item);
const updatedHighBid = ref(props.highBid);
const updatedBids = ref(props.bids);
const updatedUser = ref(props.user);
const updatedWalletBalance = ref(props.walletBalance);
const noActiveBidding = ref(props.noActiveBidding);
const youtubeChannelId = ref(props.youtubeChannelId);
let interval = null;

const fetchShowWindowData = async () => {
    try {
        const response = await axios.get("/auction/stream/bidlist");
        const data = response.data;

        updatedItem.value = data.item;
        updatedHighBid.value = data.highBid;
        updatedBids.value = data.bids;
        updatedUser.value = data.user;
        updatedWalletBalance.value = data.walletBalance;
        noActiveBidding.value = data.noActiveBidding;
    } catch (error) {
        console.error("Error fetching auction data:", error);
    }
};

const duration = ref(60);

const startAuction = () => {
    if (!updatedItem.value?.id) {
        console.error("No item ID found to start auction");
        return;
    }

    axios.post(route("auction.start", { item: updatedItem.value.id }), {
        duration: duration.value,
    });
};

onMounted(() => {
    fetchShowWindowData();
    // interval = setInterval(fetchShowWindowData, 20000);

    window.Echo.channel("auction-items").listen(".AuctionItemToggled", (e) => {
        fetchShowWindowData();
    });
});

onUnmounted(() => {
    clearInterval(interval);
});
</script>

<style scoped></style>
