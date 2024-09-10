<template>
    <div>
        <h2 class="text-2xl font-bold mb-4">Normal Bidding</h2>
        <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6"
        >
            <NormalItemCard
                v-for="item in items.data"
                :key="item.id"
                :item="item"
            />
        </div>
        <div class="mt-4">
            <Pagination :links="items.links" />
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";
import NormalItemCard from "./NormalItemCard.vue";
import Pagination from "@/Components/Kalakalkoto/Pagination.vue";

const props = defineProps({
    items: Object,
});
</script>

<style scoped></style>
