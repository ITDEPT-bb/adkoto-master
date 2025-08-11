<template>
    <Head title="Live Auction" />
    <AuthenticatedLayout>
        <div class="p-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <VideoPanel
                    :app-id="appId"
                    :auth-user="authUser"
                    @joined="onAgoraJoined"
                    @left="onAgoraLeft"
                />

                <div>
                    <TimerPanel
                        :timeRemaining="timeRemaining"
                        :running="timerActive"
                    />
                    <BiddersPanel :bidders="bidders" :highest="highestBidder" />
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
                <BiddingPanel
                    :auth-user="authUser"
                    :timer-active="timerActive"
                    :current-bid="currentBid"
                    @place-bid="placeBid"
                    @start-auction="startAuction"
                    @end-bidding="endBidding"
                    @next-item="nextItem"
                    @end-auction="endAuction"
                />

                <ItemsList
                    :items="items"
                    :current-index="currentItemIndex"
                    @select-index="(i) => (currentItemIndex = i)"
                    @set-active="setActive"
                    @set-next="setNext"
                />

                <CurrentItemPanel
                    :item="currentItem"
                    :current-bid="currentBid"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import AuthenticatedLayout from "@/Layouts/AuctionLayout.vue";

import VideoPanel from "@/Components/Auction/VideoPanel.vue";
import TimerPanel from "@/Components/Auction/TimerPanel.vue";
import BiddersPanel from "@/Components/Auction/BiddersPanel.vue";
import BiddingPanel from "@/Components/Auction/BiddingPanel.vue";
import ItemsList from "@/Components/Auction/ItemsList.vue";
import CurrentItemPanel from "@/Components/Auction/CurrentItemPanel.vue";

const props = defineProps({ appId: String, initialItems: Array });
const authUser = usePage().props.auth.user;

const items = ref(props.initialItems || []);
const currentItemIndex = ref(
    items.value.findIndex((i) => i.status === "active")
);
if (currentItemIndex.value === -1) currentItemIndex.value = 0;

const bidders = ref([]);
const currentBid = ref(
    items.value[currentItemIndex.value]?.current_price ??
        items.value[currentItemIndex.value]?.starting_price ??
        0
);
const bidError = ref("");

const timeRemaining = ref(60);
const timerActive = ref(false);
let timerInterval = null;

const currentItem = computed(() => items.value[currentItemIndex.value] || null);
const highestBidder = computed(() =>
    bidders.value.length
        ? [...bidders.value].sort((a, b) => b.amount - a.amount)[0]
        : null
);

function startTimer(duration) {
    stopTimer();
    timeRemaining.value = duration;
    timerActive.value = true;
    timerInterval = setInterval(() => {
        timeRemaining.value--;
        if (timeRemaining.value <= 0) {
            stopTimer();
            if (authUser.is_filament_admin) endBidding();
        }
    }, 1000);
}

function stopTimer() {
    if (timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
    }
    timerActive.value = false;
}

function onAgoraJoined() {
    /* optional hook */
}
function onAgoraLeft() {
    /* optional hook */
}

async function setActive(itemId) {
    await axios.post(`/auction/${itemId}/set-active`);
}

async function setNext(itemId) {
    await axios.post(`/auction/${itemId}/set-next`);
}

async function startAuction() {
    if (!currentItem.value) return;
    currentItemIndex.value = items.value.findIndex(
        (i) => i.id === currentItem.value.id
    );
    currentBid.value =
        currentItem.value.current_price ??
        currentItem.value.starting_price ??
        0;
    bidders.value = [];
    startTimer(60);
    await axios.post(`/auction/${currentItem.value.id}/set-active`);
}

async function endBidding() {
    stopTimer();
    // you can implement a dedicated endpoint to mark end-bidding if desired
    await axios
        .post(`/auction/${currentItem.value.id}/set-next`)
        .catch(() => {});
}

async function nextItem() {
    if (currentItemIndex.value < items.value.length - 1) {
        currentItemIndex.value++;
        currentBid.value =
            items.value[currentItemIndex.value].current_price ??
            items.value[currentItemIndex.value].starting_price ??
            0;
        bidders.value = [];
        startTimer(60);
        await axios.post(`/auction/next`);
    } else {
        endAuction();
    }
}

async function endAuction() {
    stopTimer();
    currentItemIndex.value = -1;
    await axios.post(`/auction/end`).catch(() => {});
}

async function placeBid(amount) {
    if (!amount || amount <= currentBid.value) {
        bidError.value = "Bid must be higher";
        return;
    }
    await axios
        .post("/bids", { item_id: currentItem.value.id, amount })
        .then(() => {
            bidError.value = "";
        })
        .catch((e) => {
            bidError.value = e.response?.data?.message || "Failed";
        });
}

// Realtime listeners
onMounted(() => {
    window.Echo.join("auction-room")
        .here((users) => {
            console.log(users);
        })
        .joining((user) => {
            console.log(user);
        })
        .leaving((user) => {
            console.log(user);
        })
        .listen(".AuctionUpdated", (e) => {
            console.log("Event handled", e.type, {
                items: [...items.value],
                bidders: [...bidders.value],
                currentBid: currentBid.value,
                currentIndex: currentItemIndex.value,
            });
            switch (e.type) {
                case "active-item":
                    items.value = items.value.map((i) =>
                        i.id === e.payload.id ? e.payload : i
                    );
                    currentItemIndex.value = items.value.findIndex(
                        (i) => i.status === "active"
                    );
                    currentBid.value =
                        items.value[currentItemIndex.value]?.current_price ??
                        items.value[currentItemIndex.value]?.starting_price ??
                        0;
                    break;
                case "next-item":
                    items.value = items.value.map((i) =>
                        i.id === e.payload.id ? e.payload : i
                    );
                    break;
                case "bid":
                    bidders.value.unshift({
                        id: e.payload.userId,
                        name: e.payload.name,
                        amount: e.payload.amount,
                        time: e.payload.time,
                    });
                    currentBid.value = e.payload.amount;
                    break;
                case "end":
                    stopTimer();
                    currentItemIndex.value = -1;
                    break;
            }
        });
});

onBeforeUnmount(() => {
    try {
        window.Echo.leave("auction-room");
    } catch (err) {}
    stopTimer();
});
</script>
