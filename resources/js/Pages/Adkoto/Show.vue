<!-- resources/js/Pages/Adkoto/Show.vue -->
<template>
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <div class="p-6">
                    <AdItem :ad="ad" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { defineProps } from "vue";
import AdItem from "@/Components/Adkoto/AdItem.vue";
import AuthenticatedLayout from "@/Layouts/AdkotoLayout.vue";

const props = defineProps({
    ad: Object,
});
</script>
