<template>
    <Head title="Create Item" />

    <KalakalLayout>
        <SidebarList />
        <CreateItemForm />
    </KalakalLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import KalakalLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import { Head } from "@inertiajs/vue3";
import CreateItemForm from "@/Components/Kalakalkoto/CreateItemForm.vue";
import SidebarList from "@/Components/Kalakalkoto/SideBarList.vue";
</script>
