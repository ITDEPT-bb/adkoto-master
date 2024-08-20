<template>
    <Head title="Kalakalkoto" />

    <KalakalLayout>
        <div class="max-w-7xl mx-auto h-full overflow-y-auto p-4">
            <div class="grid grid-cols-12 gap-6">
                <Listings :advertisements="advertisements" class="col-span-8" />
                <div class="col-span-4">
                    <ManageCard class="my-2" />
                    <SponsoredAds :sponsoredAds="sponsoredAds" />
                </div>
            </div>
        </div>
    </KalakalLayout>

    <UpdateProfileReminder />
</template>

<script setup>
import { ref } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import KalakalLayout from "@/Layouts/AdkotoLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import CategoryList from "@/Components/Adkoto/CategoryList.vue";
import Listings from "@/Components/Adkoto/Listings.vue";
import SponsoredAds from "@/Components/Adkoto/SponsoredAds.vue";
import ManageCard from "@/Components/Adkoto/ManageCard.vue";

const { props } = usePage();
const advertisements = ref(props.advertisements);
const categories = ref(props.categories);

console.log(categories.value);
</script>
