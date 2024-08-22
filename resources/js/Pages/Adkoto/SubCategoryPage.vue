<template>
    <Head title="Adkoto" />

    <KalakalLayout>
        <div class="max-w-7xl mx-auto h-full overflow-y-auto p-4">
            <div class="grid grid-cols-12 gap-6">
                <CategoryDrawer
                    :categories="categories"
                    class="lg:col-span-12 col-span-12"
                />
                <div class="lg:col-span-4 hidden lg:block">
                    <ManageCard />
                    <SponsoredAds :sponsoredAds="sponsoredAds" />
                </div>
                <Listings
                    :advertisements="advertisements"
                    :subCategory="subCategory"
                    class="lg:col-span-8 col-span-12"
                />
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
import CategoryDrawer from "@/Components/Adkoto/CategoryDrawer.vue";
import Listings from "@/Components/Adkoto/Listings.vue";
import SponsoredAds from "@/Components/Adkoto/SponsoredAds.vue";
import ManageCard from "@/Components/Adkoto/ManageCard.vue";

const { props } = usePage();
const advertisements = ref(props.advertisements);
const subCategory = ref(props.subCategory);
const categories = ref(props.categories);
</script>
