<!-- resources/js/Components/Adkoto/AdCard.vue -->
<template>
    <div class="border dark:border-slate-700 p-4 rounded-lg mb-4">
        <h2 class="text-xl font-semibold mb-2">{{ ad.title }}</h2>
        <p class="text-gray-700 dark:text-gray-300 mb-2">
            {{ ad.description }}
        </p>
        <p class="text-gray-500 dark:text-gray-400 mb-2">
            Price: {{ ad.price }}
        </p>
        <p class="text-gray-500 dark:text-gray-400 mb-2">
            Location: {{ ad.location }}
        </p>

        <div v-if="ad.attachments.length > 0" class="mt-4">
            <h3 class="text-lg font-semibold mb-2">Attachments:</h3>
            <div class="grid grid-cols-2 gap-2">
                <div v-for="attachment in ad.attachments" :key="attachment.id">
                    <img
                        :src="`/storage/${attachment.image_path}`"
                        alt="Ad Attachment"
                        class="w-full h-auto rounded-lg"
                    />
                </div>
            </div>
        </div>

        <Link
            :href="route('adkoto.show', ad.id)"
            class="text-blue-500 hover:text-blue-700 mt-4 block"
        >
            View Details
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
