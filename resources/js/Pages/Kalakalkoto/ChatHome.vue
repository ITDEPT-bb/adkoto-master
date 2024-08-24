<template>
    <Head title="Chat" />

    <AuthenticatedLayout>
        <div
            class="home-page flex h-full overflow-y-auto container p-4 mx-auto"
        >
            <BuyerList :participants="participants" />
        </div>
    </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { defineProps, ref } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import BuyerList from "@/Components/Kalakalkoto/BuyerList.vue";

const { props } = usePage();
const participants = ref(props.participants);
</script>
