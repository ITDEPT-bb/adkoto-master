<template>
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto gap-3 p-4 h-full">
            <h1 class="text-2xl font-bold mb-4">Ads</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <AdCard v-for="ad in ads" :key="ad.id" :ad="ad" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { defineProps } from "vue";
import AdCard from "@/Components/Adkoto/AdCard.vue";
import AuthenticatedLayout from "@/Layouts/AdkotoLayout.vue";

const props = defineProps({
    ads: Array,
});
</script>
