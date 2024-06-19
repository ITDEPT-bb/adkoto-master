<template>
    <div
        class="bg-white border dark:bg-slate-950 dark:border-slate-900 dark:text-gray-100 rounded p-4 mb-3"
    >
        <ul>
            <li v-for="ad in ads" :key="ad.id" class="mb-4">
                <AdItem :ad="ad" />
            </li>
        </ul>
        <Link
            :href="route('adkoto.create')"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
            Create New Ad
        </Link>
    </div>
</template>

<script setup>
import { defineProps } from "vue";
import { Link, usePage } from '@inertiajs/vue3';
import AdItem from "@/Components/Adkoto/AdItem.vue";

const props = defineProps({
    ads: Array,
});
</script>
