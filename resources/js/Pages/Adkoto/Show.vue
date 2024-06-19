<!-- resources/js/Pages/Adkoto/Show.vue -->
<template>
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto gap-3 p-4 overflow-y-auto h-full">
            <AdItem :ad="ad" />
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
