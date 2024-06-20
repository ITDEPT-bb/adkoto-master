<template>
    <div
        class="relative border dark:border-slate-700 rounded-lg mb-4 overflow-hidden shadow-lg"
    >
        <!-- <Link :href="route('adkoto.show', ad.id)" class="block w-full h-full"> -->
            <div
                v-if="ad.attachments.length > 0"
                class="w-full h-48 overflow-hidden"
            >
                <img
                    :src="`/storage/${ad.attachments[0].image_path}`"
                    alt="Ad Attachment"
                    class="w-full h-full object-contain"
                />
            </div>
            <div
                class="absolute bottom-0 left-0 right-0 top-0 bg-blue-500 bg-opacity-0 hover:bg-opacity-90 text-white p-2 opacity-0 hover:opacity-100 transition-opacity duration-300"
            >
                <h2 class="text-xl font-semibold">{{ ad.title }}</h2>
                <!-- <p class="text-white">{{ ad.description }}</p> -->
                <p class="text-white">Price: {{ ad.price }}</p>
                <p class="text-white">Location: {{ ad.location }}</p>
                <button
                    @click="editAd(ad)"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded mr-2"
                >
                    Edit
                </button>
                <button
                    @click="confirmDelete(ad.id)"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                >
                    Delete
                </button>
            </div>
        <!-- </Link> -->

        <!-- <div class="absolute top-2 right-2">
            <button @click="editAd(ad)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-2">
                Edit
            </button>
            <button @click="confirmDelete(ad.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                Delete
            </button>
        </div> -->
    </div>
</template>

<script setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    ad: Object,
});

const editAd = (ad) => {
    // Implement logic to open a modal for editing the ad
    console.log(`Editing ad with ID ${ad.id}`);
};

const confirmDelete = (adId) => {
    if (confirm(`Are you sure you want to delete ad with ID ${adId}?`)) {
        // Implement logic to delete the ad (usually via API call)
        console.log(`Deleting ad with ID ${adId}`);
    }
};
</script>

<style scoped>
/* No scoped styles needed here if using Tailwind classes */
</style>
