<template>
    <div class="bg-white p-4 rounded">
        <div v-if="authUser.is_filament_admin">
            <h3 class="font-semibold mb-2">Host Controls</h3>
            <div class="grid grid-cols-2 gap-2">
                <button
                    @click="$emit('start-auction')"
                    class="px-3 py-2 bg-green-600 text-white rounded"
                >
                    Start
                </button>
                <button
                    @click="$emit('end-bidding')"
                    class="px-3 py-2 bg-red-600 text-white rounded"
                >
                    End
                </button>
                <button
                    @click="$emit('next-item')"
                    class="col-span-2 px-3 py-2 bg-blue-600 text-white rounded"
                >
                    Next Item
                </button>
                <button
                    @click="$emit('end-auction')"
                    class="col-span-2 px-3 py-2 bg-gray-700 text-white rounded"
                >
                    End Auction
                </button>
            </div>
        </div>

        <div v-else>
            <h3 class="font-semibold mb-2">Place a Bid</h3>
            <input
                v-model.number="value"
                type="number"
                class="w-full p-2 border rounded mb-2"
                placeholder="Enter your bid"
            />
            <button
                @click="onPlace"
                class="w-full p-2 bg-blue-600 text-white rounded"
                :disabled="!timerActive"
            >
                Place Bid
            </button>
            <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
const props = defineProps({ authUser: Object, timerActive: Boolean });
const emit = defineEmits([
    "place-bid",
    "start-auction",
    "end-bidding",
    "next-item",
    "end-auction",
]);
const value = ref("");
const error = ref("");

function onPlace() {
    const v = Number(value.value);
    if (!v) {
        error.value = "Enter a valid amount";
        return;
    }
    error.value = "";
    emit("place-bid", v);
    value.value = "";
}
</script>
