<template>
    <div class="bg-white p-4 rounded">
        <h3 class="font-semibold mb-2">Current Item</h3>
        <div v-if="item">
            <img :src="item.attachments[0].value" alt="test">
            <h4 class="font-bold">{{ item.title }}</h4>
            <p class="text-sm">{{ item.description }}</p>
            <div class="mt-2">Starting: ₱{{ item.starting_price }}</div>
            <div class="mt-1 text-lg font-bold text-green-600">
                Current: ₱{{ currentBid }}
            </div>
        </div>
        <div v-else class="text-gray-500">No active item</div>
    </div>
</template>

<script setup>
const props = defineProps({ item: Object, currentBid: [Number, String] });
</script>
