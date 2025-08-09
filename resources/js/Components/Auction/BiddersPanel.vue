<template>
    <div class="bg-white p-4 rounded">
        <h3 class="font-semibold">Bidders</h3>
        <div
            v-if="bidders && bidders.length"
            class="space-y-2 max-h-48 overflow-auto"
        >
            <div
                v-for="(b, i) in bidders"
                :key="i"
                class="flex justify-between"
            >
                <div>{{ b.name }}</div>
                <div class="font-semibold">₱{{ b.amount }}</div>
            </div>
        </div>
        <div v-else class="text-gray-500">No bids yet</div>
    </div>
</template>
<script setup>
const props = defineProps({ bidders: Array, highest: Object });
</script>
