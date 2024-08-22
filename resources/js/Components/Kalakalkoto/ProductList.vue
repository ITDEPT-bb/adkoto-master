<template>
    <div class="p-4 rounded-lg dark:border-gray-700">
        <div class="flex space-x-4 mb-2">
            <h1 class="text-lg font-semibold">
                {{ category ? category.name : "Listings" }}
            </h1>
        </div>

        <div class="grid lg:grid-cols-4 gap-4 mb-4">
            <KalakalItem
                v-for="item in kalakalitems.data"
                :key="item.id"
                :item="item"
            />
        </div>

        <!-- Pagination Links -->
        <div class="mt-4">
            <Pagination :links="kalakalitems.links" />
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import KalakalItem from "./KalakalItem.vue";
import Pagination from "./Pagination.vue";

const { props } = usePage();

const category = ref(props.category);
const kalakalitems = ref(props.kalakalitems);
</script>
