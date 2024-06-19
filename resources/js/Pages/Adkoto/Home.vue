<template>
    <AuthenticatedLayout>
        <Link
                :href="route('adkoto.create')"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4 block"
            >
                Create New Ad
            </Link>
        <div class="max-w-7xl mx-auto gap-3 p-4 overflow-y-auto h-full">
            <h1 class="text-2xl font-bold mb-4">Ads</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                <AdCard v-for="ad in ads" :key="ad.id" :ad="ad" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { defineProps } from "vue";
import { Link } from '@inertiajs/vue3';
import AdCard from "@/Components/Adkoto/AdCard.vue";
import AuthenticatedLayout from "@/Layouts/AdkotoLayout.vue";

const props = defineProps({
    ads: Array,
});
</script>
