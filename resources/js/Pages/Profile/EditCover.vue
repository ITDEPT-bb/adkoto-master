<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
</script>

<template>
    <Head title="Update Your Cover" />

    <AuthenticatedLayout> </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<style></style>
