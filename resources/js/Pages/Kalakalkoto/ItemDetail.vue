<template>
    <Head title="Item Detail" />

    <KalakalLayout>
        <SidebarList />

        <div class="p-4 h-full overflow-y-auto sm:ml-64">
            <div
                class="max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4"
            >
                <div class="flex flex-col items-center">
                    <div class="w-full max-w-lg mb-4">
                        <img
                            :src="`/storage/${
                                item.images[0]?.path || 'placeholder.png'
                            }`"
                            :alt="item.title"
                            class="w-full h-80 object-cover rounded-lg"
                        />
                    </div>
                    <div class="flex flex-col items-start space-y-4 w-full">
                        <h2
                            class="text-2xl font-semibold text-gray-800 dark:text-gray-200"
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
                        <p class="text-gray-800 dark:text-gray-200">
                            {{ item.description }}
                        </p>
                        <div class="flex space-x-4">
                            <button
                                @click="editItem"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg"
                            >
                                Edit
                            </button>
                            <button
                                @click="deleteItem"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg"
                            >
                                Delete
                            </button>
                            <button
                                @click="markAsSold"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg"
                            >
                                Mark as Sold
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </KalakalLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { ref } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import axiosClient from "@/axiosClient.js";
import KalakalLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import SidebarList from "@/Components/Kalakalkoto/SideBarList.vue";

const { props } = usePage();
const item = ref(props.item);

const formatPrice = (price) => {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
        minimumFractionDigits: 2,
    }).format(price);
};

const editItem = () => {
    window.location.href = `/kalakalkoto/item/${item.value.id}/edit`;
};

const deleteItem = async () => {
    if (confirm("Are you sure you want to delete this item?")) {
        try {
            const response = await axiosClient.delete(`/kalakalkoto/item/${item.value.id}`);
            if (response.data.redirect) {
                window.location.href = response.data.redirect;
            }
        } catch (error) {
            console.error("There was an error deleting the item:", error);
        }
    }
};

const markAsSold = async () => {
    if (confirm("Are you sure you want to mark this item as sold?")) {
        try {
            const response = await axiosClient.post(`/kalakalkoto/item/${item.value.id}/mark-as-sold`);
            if (response.data.redirect) {
                window.location.href = response.data.redirect;
            }
        } catch (error) {
            console.error("There was an error marking the item as sold:", error);
        }
    }
};
</script>

<style scoped></style>
