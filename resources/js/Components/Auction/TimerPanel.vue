<template>
    <div class="bg-white p-4 rounded mb-4">
        <h3 class="font-semibold">Auction Timer</h3>
        <div class="text-2xl font-bold">{{ display }}</div>
    </div>
</template>

<script setup>
import { computed } from "vue";
const props = defineProps({ timeRemaining: Number, running: Boolean });
const display = computed(() => {
    const s = props.timeRemaining || 0;
    const m = Math.floor(s / 60)
        .toString()
        .padStart(2, "0");
    const sec = (s % 60).toString().padStart(2, "0");
    return `${m}:${sec}`;
});
</script>
