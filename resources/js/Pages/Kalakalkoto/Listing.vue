<script setup>
import { ref } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import KalakalLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

import SidebarList from "@/Components/Kalakalkoto/SideBarList.vue";

const { props } = usePage();
const categories = ref(props.categories || []);
const items = ref(props.items || []);
</script>

<template>
    <Head title="Kalakalkoto" />

    <KalakalLayout>
        <SidebarList />

        <div class="p-4 h-full overflow-y-auto sm:ml-64">

        </div>
    </KalakalLayout>
    <UpdateProfileReminder />
</template>

<style scoped></style>
