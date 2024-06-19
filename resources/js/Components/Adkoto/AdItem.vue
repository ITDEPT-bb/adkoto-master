<!-- resources/js/Components/Adkoto/AdItem.vue -->
<template>
    <div class="border dark:border-slate-700 p-4 rounded-lg mb-4">
        <h2 class="text-2xl font-semibold mb-4">{{ ad.title }}</h2>
        <p class="text-gray-700 dark:text-gray-300 mb-4">
            {{ ad.description }}
        </p>
        <p class="text-gray-500 dark:text-gray-400 mb-4">
            Price: {{ ad.price }}
        </p>
        <p class="text-gray-500 dark:text-gray-400 mb-4">
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
    </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    ad: Object,
});
</script>
