<template>
    <div class="relative border dark:border-slate-700 m-4 rounded-lg mb-4 overflow-hidden">
        <Link :href="route('adkoto.show', ad.id)" class="block w-full h-full">
            <div v-if="ad.attachments.length > 0" class="w-full h-full relative">
                <img
                    :src="`/storage/${ad.attachments[0].image_path}`"
                    alt="Ad Attachment"
                    class="w-full h-full object-contain"
                />
                <!-- <div class="absolute bottom-0 left-0 right-0 top-0 bg-black bg-opacity-0 hover:bg-opacity-90 text-white p-2 opacity-0 hover:opacity-100 transition-opacity duration-300"> -->
                <div class="absolute bottom-0 left-0 right-0 top-0 bg-blue-500 bg-opacity-0 hover:bg-opacity-90 text-white p-2 opacity-0 hover:opacity-100 transition-opacity duration-300">
                    <h2 class="text-sm text-center sm:mt-8 font-semibold">{{ ad.title }}</h2>
                </div>
            </div>
        </Link>
    </div>
</template>

<script setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    ad: Object,
});
</script>

<style scoped>
.card-img {
    height: 200px;
}
</style>
