<template>
    <Head title="View Advertisements" />

    <AdkotoLayout>
        <div class="max-w-7xl mx-auto h-full overflow-y-auto p-4">
            <div class="grid grid-cols-12 gap-6">
                <AdvertisementCard
                    :advertisements="advertisements"
                    class="lg:col-span-8 col-span-12"
                />
                <SponsoredAds
                    :sponsoredAds="sponsoredAds"
                    class="lg:col-span-4 hidden lg:block"
                />
            </div>
        </div>
    </AdkotoLayout>

    <UpdateProfileReminder />
</template>

<script setup>
import { ref } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import AdkotoLayout from "@/Layouts/AdkotoLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import SponsoredAds from "@/Components/Adkoto/SponsoredAds.vue";
import AdvertisementCard from "@/Components/Adkoto/AdvertisementCard.vue";

const { props } = usePage();
const advertisements = ref(props.advertisements);
const categories = ref(props.categories);
// const sponsoredAds = ref(props.sponsoredAds);

console.log(categories.value);
</script>
