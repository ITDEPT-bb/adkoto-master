<!-- resources/js/Components/Adkoto/AdItem.vue -->
<template>
    <div class="border dark:border-slate-700 rounded-lg overflow-hidden">
        <h2
            class="text-xl font-semibold mb-4 px-4 py-2 bg-gray-200 dark:bg-gray-700"
        >
            {{ ad.title }}
        </h2>
        <div class="p-4">
            <p class="text-gray-600 dark:text-gray-300 mb-2">
                {{ ad.description }}
            </p>
            <!-- <p class="text-gray-700 dark:text-gray-400 mb-2">
                Price: {{ ad.price }}
            </p> -->
            <p class="text-gray-700 dark:text-gray-400 mb-2">
                Location: {{ ad.location }}
            </p>

            <div v-if="ad.attachments.length > 0" class="mt-4">
                <h3 class="text-lg font-semibold mb-2">Attachments:</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div
                        v-for="attachment in ad.attachments"
                        :key="attachment.id"
                    >
                        <img
                            :src="`/storage/${attachment.image_path}`"
                            alt="Ad Attachment"
                            class="w-full h-auto rounded-lg"
                        />
                    </div>
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

<style scoped>
/* Scoped styles */
.border {
    border-width: 1px;
    border-style: solid;
}

</style>
