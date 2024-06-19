<!-- resources/js/Pages/Adkoto/Show.vue -->
<template>
    <AuthenticatedLayout>
        <div>
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
