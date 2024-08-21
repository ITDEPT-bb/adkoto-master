<template>
    <aside class="bg-white p-4 shadow my-2 rounded-lg">
        <h2 class="text-2xl font-bold mb-4">Sponsored Ads</h2>
        <div class="space-y-4">
            <div
                v-for="ad in sponsoredAds"
                :key="ad.id"
                class="flex items-center space-x-4"
            >
                <img
                    :src="ad.image || 'https://via.placeholder.com/100'"
                    alt="Sponsored Ad Image"
                    class="w-16 h-16 object-cover rounded"
                />
                <div>
                    <h3 class="text-lg font-semibold">{{ ad.title }}</h3>
                    <p class="text-sm text-gray-600">{{ ad.description }}</p>
                </div>
            </div>
        </div>
    </aside>
</template>

<script setup>
const props = defineProps({
    sponsoredAds: Array,
});
</script>
