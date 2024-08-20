<template>
    <div>
        <!-- Drawer Toggle Button -->
        <button
            @click="toggleDrawer"
            class="fixed bottom-4 right-4 bg-blue-500 text-white p-2 rounded"
        >
            Categories
        </button>

        <!-- Drawer -->
        <div
            v-if="isOpen"
            class="fixed inset-0 bg-gray-800 bg-opacity-75 z-50 flex justify-end"
        >
            <div class="bg-white w-80 h-full p-4">
                <button
                    @click="toggleDrawer"
                    class="absolute top-4 right-4 text-gray-600"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                </button>
                <h1 class="text-3xl font-bold mb-2">
                    Advertisement Categories
                </h1>
                <div class="grid grid-cols-1 gap-4">
                    <div
                        v-for="category in categories"
                        :key="category.id"
                        class="p-4"
                    >
                        <a
                            :href="
                                route('adkoto.showCategory', {
                                    category_name: category.name,
                                })
                            "
                        >
                            <h2
                                class="text-md font-semibold mb-2 text-blue-500 hover:underline"
                            >
                                {{ category.name }} ({{
                                    category.advertisements_count
                                }})
                            </h2>
                        </a>
                        <ul class="pl-5 list-disc">
                            <li
                                v-for="subCategory in category.sub_categories"
                                :key="subCategory.id"
                                class="text-gray-700 text-xs pb-1 hover:underline"
                            >
                                <a
                                    :href="
                                        route('adkoto.showSubcategory', {
                                            category_name: category.name,
                                            subcategory_name: subCategory.name,
                                        })
                                    "
                                >
                                    <span
                                        >{{ subCategory.name }} ({{
                                            subCategory.advertisements_count
                                        }})</span
                                    >
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    categories: Array,
});

const isOpen = ref(false);

function toggleDrawer() {
    isOpen.value = !isOpen.value;
}
</script>

<style scoped></style>
