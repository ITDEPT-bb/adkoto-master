<!-- resources/js/Pages/Adkoto/Show.vue -->
<template>
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 py-8 h-full overflow-y-auto">
            <Link
                :href="route('adkoto')"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-5"
            >
                Back
            </Link>
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <div class="p-6">
                    <AdItem :ad="ad" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";
import AdItem from "@/Components/Adkoto/AdItem.vue";
import AuthenticatedLayout from "@/Layouts/AdkotoLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

const props = defineProps({
    ad: Object,
});
</script>
