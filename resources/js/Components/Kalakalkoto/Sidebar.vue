<template>
    <aside
        id="default-sidebar"
        class="fixed left-0 top-16 z-40 w-64 shadow-xl h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar"
    >
        <div
            class="h-full px-3 py-4 overflow-y-auto bg-white dark:bg-gray-800"
        >
            <ul class="space-y-2 font-medium">
                <li>
                    <a
                        href="#"
                        class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group"
                    >
                        <span class="ms-3 text-sm">Upgrade to Pro</span>
                    </a>
                </li>
            </ul>

            <!-- Separator -->
            <ul
                class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700"
            >
                <li v-for="category in categories" :key="category.id">
                    <a
                        :href="`/categories/${category.id}`"
                        class="flex items-center p-1  text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group"
                    >
                        <span class="ms-3 text-sm">{{ category.name }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
});
</script>

<style scoped></style>
