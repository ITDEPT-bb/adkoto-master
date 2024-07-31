<template>
    <Head title="Advertisements" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto p-4 h-full overflow-y-auto">
            <div class="flex justify-end gap-3 mx-auto">
                <!-- Create New Ad Button -->
                <Link
                    :href="route('adkoto.create')"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition duration-300 ease-in-out"
                >
                    Create New Ad
                </Link>

                <Link
                    :href="route('ads.fetchAll')"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition duration-300 ease-in-out"
                >
                    My Advertisements
                </Link>
            </div>

            <!-- Categories Section -->
            <div class="mt-8">
                <h2 class="text-xl font-bold mb-4">Categories</h2>
                <ul class="flex flex-wrap gap-2">
                    <li v-for="category in categories" :key="category.id">
                        <button
                            @click="selectCategory(category.id)"
                            :class="`px-4 py-2 rounded-lg font-semibold transition duration-300 ease-in-out ${
                                selectedCategory === category.id
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-gray-200 hover:bg-gray-300 text-gray-700'
                            }`"
                        >
                            {{ category.name }}
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Advertisements Section -->
            <div class="mt-8">
                <h1 class="text-2xl font-bold mb-4">
                    {{
                        selectedCategory
                            ? getCategoryName(selectedCategory)
                            : "Advertisements"
                    }}
                </h1>

                <div
                    v-if="filteredAds.length > 0"
                    class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-12 gap-6"
                >
                    <!-- Rectangle Size Ads Section -->
                    <div class="col-span-12 md:col-span-8 lg:col-span-8">
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6"
                        >
                            <AdCard
                                v-for="ad in rectangleSizeAds"
                                :key="ad.id"
                                :ad="ad"
                            />
                        </div>
                    </div>

                    <!-- Box Size Ads Section -->
                    <div class="col-span-12 md:col-span-4 lg:col-span-4">
                        <div class="grid grid-cols-2 md:grid-cols-2 gap-6">
                            <AdCard
                                v-for="ad in boxSizeAds"
                                :key="ad.id"
                                :ad="ad"
                            />
                        </div>
                    </div>
                </div>

                <div v-else class="text-gray-600 mt-4">
                    No ads found in this category.
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { defineProps, ref, computed, onMounted } from "vue";
import { Link, Head } from "@inertiajs/vue3";
import AdCard from "@/Components/Adkoto/AdCard.vue";
import AuthenticatedLayout from "@/Layouts/AdkotoLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

const props = defineProps({
    ads: Array,
    categories: Array,
});

const selectedCategory = ref(null);
const adsWithDimensions = ref([]);

const filteredAds = computed(() => {
    if (!selectedCategory.value) {
        return adsWithDimensions.value;
    } else {
        return adsWithDimensions.value.filter(
            (ad) => ad.category_id === selectedCategory.value
        );
    }
});

const boxSizeAds = computed(() => {
    const tolerance = 50;
    return filteredAds.value.filter(
        (ad) => Math.abs(ad.attachmentHeight - ad.attachmentWidth) <= tolerance
    );
});

const rectangleSizeAds = computed(() => {
    const ratioTolerance = 1.5;
    return filteredAds.value.filter((ad) => {
        const widthHeightRatio = ad.attachmentWidth / ad.attachmentHeight;
        const heightWidthRatio = ad.attachmentHeight / ad.attachmentWidth;
        return (
            widthHeightRatio >= ratioTolerance ||
            heightWidthRatio >= ratioTolerance
        );
    });
});

function selectCategory(categoryId) {
    selectedCategory.value =
        categoryId === selectedCategory.value ? null : categoryId;
}

function getCategoryName(categoryId) {
    const category = props.categories.find((cat) => cat.id === categoryId);
    return category ? category.name : "";
}

// Function to get image dimensions
async function getImageDimensions(url) {
    return new Promise((resolve) => {
        const img = new Image();
        img.onload = () => resolve({ width: img.width, height: img.height });
        img.src = url;
    });
}

// Fetch and set ad dimensions
onMounted(async () => {
    const ads = await Promise.all(
        props.ads.map(async (ad) => {
            if (ad.attachments.length > 0) {
                const dimensions = await getImageDimensions(
                    `/storage/${ad.attachments[0].image_path}`
                );
                return {
                    ...ad,
                    attachmentWidth: dimensions.width,
                    attachmentHeight: dimensions.height,
                };
            }
            return ad;
        })
    );
    adsWithDimensions.value = ads;
});
</script>

<style scoped>
/* Additional styles to improve responsiveness */
@media (max-width: 768px) {
    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    .grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
</style>
