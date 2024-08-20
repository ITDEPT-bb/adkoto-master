<template>
    <main class="bg-white p-4 shadow rounded-lg">
        <section>
            <div class="flex space-x-4 mb-4">
                <h1 class="text-lg font-semibold">Advertisement Listings</h1>
                <!-- <button class="text-lg">Popular</button>
                <button class="text-lg">Random</button> -->
            </div>
            <div
                v-for="advertisement in advertisements"
                :key="advertisement.id"
            >
                <ListingItem :advertisement="advertisement" />
            </div>
        </section>
    </main>
</template>

<script setup>
import ListingItem from "./ListingItem.vue";

const props = defineProps({
    advertisements: Array,
});
</script>
