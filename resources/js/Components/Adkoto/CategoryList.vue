<template>
    <aside class="shadow rounded-lg bg-white">
        <section class="p-4">
            <h1 class="text-3xl font-bold mb-2">Advertisement Categories</h1>
            <div class="grid grid-cols-3">
                <div
                    v-for="category in categories"
                    :key="category.id"
                    class="p-4"
                >
                    <h2 class="text-xl font-semibold mb-2 text-red-600">
                        {{ category.name }} ({{
                            category.sub_categories.length
                        }})
                    </h2>
                    <ul class="pl-3">
                        <li
                            v-for="subCategory in category.sub_categories"
                            :key="subCategory.id"
                            class="text-gray-700 text-sm py-1"
                        >
                            <span>{{ subCategory.name }} (0)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </aside>
</template>

<script setup>
import { ref } from "vue";
const props = defineProps({
    categories: Array,
});
</script>
