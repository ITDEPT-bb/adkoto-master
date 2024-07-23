<template>
    <Head title="Kalakalkoto" />

    <KalakalLayout>
        <SidebarList />

        <div class="p-4 h-full overflow-y-auto sm:ml-64">
            <div class="grid lg:grid-cols-3 gap-4 mb-4">
                <div
                    v-for="item in items"
                    :key="item.id"
                    class="flex flex-col items-start p-4 max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg"
                >
                    <Link :href="`/kalakalkoto/user/item/${item.id}`">
                        <div class="w-full h-40 overflow-hidden rounded-t-lg">
                            <img
                                :src="`/storage/${
                                    item.images[0]?.path || 'placeholder.png'
                                }`"
                                :alt="item.title"
                                class="w-full h-full object-cover rounded-t-lg"
                            />
                        </div>
                        <div class="p-4 flex flex-col items-start space-y-2">
                            <h2
                                class="text-lg font-semibold text-gray-800 dark:text-gray-200"
                            >
                                {{ item.title }}
                            </h2>
                            <p
                                class="text-lg font-bold text-gray-900 dark:text-gray-100"
                            >
                                {{ formatPrice(item.price) }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Location: {{ item.location }}
                            </p>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </KalakalLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { ref } from "vue";
import { defineProps } from "vue";
import { usePage, Head, Link } from "@inertiajs/vue3";
import KalakalLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import SidebarList from "@/Components/Kalakalkoto/SideBarList.vue";

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
});

const formatPrice = (price) => {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
        minimumFractionDigits: 2,
    }).format(price);
};
</script>

<style scoped></style>
