<template>
    <Head title="Kalakalkoto" />

    <AuthenticatedLayout>
        <div
            class="max-w-7xl mx-auto h-full overflow-y-auto p-4 scrollbar-thin"
        >
            <!-- <PageSelector /> -->
            <AuctionMenu />
            <div class="bg-white dark:bg-slate-950 p-6 rounded-lg shadow-sm">
                <NormalItemList :items="items" />
            </div>
        </div>
    </AuthenticatedLayout>

    <UpdateProfileReminder />
</template>

<script setup>
import { ref } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

import AuctionMenu from "@/Components/Auction/AuctionMenu.vue";
import PageSelector from "@/Components/Kalakalkoto/PageSelector.vue";
import NormalItemList from "@/Components/Auction/NormalItemList.vue";
import NormalItemCard from "@/Components/Auction/NormalItemCard.vue";

const { props } = usePage();
const items = ref(props.items);
</script>
