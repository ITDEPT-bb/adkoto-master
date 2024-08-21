<template>
    <Head title="Kalakalkoto" />

    <KalakalLayout>
        <div class="max-w-7xl mx-auto h-full overflow-y-auto p-4">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-8">
                    <div class="bg-blue-400 p-4">
                        <h1 class="text-lg text-white font-semibold">
                            You can manage your Advertisements
                        </h1>
                    </div>
                    <!-- <div v-if="advertisements.length > 0">
                        <Listings :advertisements="advertisements" />
                    </div>
                    <div v-else class="text-center text-gray-600 py-4">
                        <div class="bg-white p-4 shadow rounded-lg">
                            You don't have any advertisements posted.
                        </div>
                    </div> -->
                    <Listings
                        :advertisements="advertisements"
                        class="lg:col-span-8 col-span-12"
                    />
                </div>
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
import Listings from "@/Components/Adkoto/Listings.vue";
import SponsoredAds from "@/Components/Adkoto/SponsoredAds.vue";
import ManageCard from "@/Components/Adkoto/ManageCard.vue";

const { props } = usePage();
const advertisements = ref(props.advertisements);
</script>
