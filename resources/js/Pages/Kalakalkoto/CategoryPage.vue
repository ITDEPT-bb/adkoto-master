<template>
    <Head title="Categories" />

    <AdkotoLayout>
        <div class="max-w-7xl mx-auto h-full overflow-y-auto p-4">
            <KalakalMenu />
            <CategoryMenu :categories="categories" />
            <ProductList :kalakalitems="kalakalitems" />
        </div>
    </AdkotoLayout>

    <UpdateProfileReminder />
</template>

<script setup>
import { ref } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import AdkotoLayout from "@/Layouts/AdkotoLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

import CategoryMenu from "@/Components/Kalakalkoto/CategoryMenu.vue";
import ProductList from "@/Components/Kalakalkoto/ProductList.vue";
import KalakalMenu from "@/Components/Kalakalkoto/KalakalMenu.vue";

const { props } = usePage();
const categories = ref(props.categories);
const kalakalitems = ref(props.kalakalitems);
</script>
