<template>
    <Head title="Advertisements" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto p-4 h-full overflow-y-auto">
            <Link
                :href="route('adkoto')"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2 inline-block"
            >
                Back
            </Link>

            <div class="mt-8">
                <h2 class="text-xl mt-8 font-bold mb-4">My Advertisements</h2>

                <div v-if="ads.length === 0">
                    <p>No ads found.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div v-for="ad in ads" :key="ad.id">
                        <AdCardItems :ad="ad" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import AdCardItems from "@/Components/Adkoto/AdCardItems.vue";
import { usePage, Link, Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AdkotoLayout.vue";

const { props } = usePage();
const ads = ref(props.ads);

const editAd = (ad) => {
    console.log(`Editing ad with ID ${ad.id}`);
};

const confirmDelete = (adId) => {
    if (confirm(`Are you sure you want to delete ad with ID ${adId}?`)) {
        console.log(`Deleting ad with ID ${adId}`);
        ads.value = ads.value.filter(ad => ad.id !== adId);
    }
};
</script>

<style scoped>

</style>
