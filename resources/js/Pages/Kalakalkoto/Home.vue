<script setup>
import { ref } from "vue";
import { usePage, Link, Head } from "@inertiajs/vue3";
import KalakalLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

import Sidebar from "@/Components/Kalakalkoto/Sidebar.vue";
import ItemList from "@/Components/Kalakalkoto/ItemList.vue";

const { props } = usePage();
const categories = ref(props.categories || []);
const items = ref(props.items || []);
</script>

<template>
    <Head title="Kalakalkoto" />

    <KalakalLayout>
        <Sidebar :categories="categories" />

        <div class="p-4 h-full overflow-y-auto sm:ml-64">
            <ItemList :items="items" />
        </div>
    </KalakalLayout>
    <UpdateProfileReminder />
</template>

<style scoped></style>
