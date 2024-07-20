<template>
    <Head title="Update Item" />

    <KalakalLayout>
        <SidebarList />

        <div class="p-4 h-full overflow-y-auto sm:ml-64">
            <div
                class="max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4"
            >
                <h2
                    class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4"
                >
                    Edit Item
                </h2>
                <form @submit.prevent="updateItem">
                    <div class="mb-4">
                        <label
                            for="title"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Title</label
                        >
                        <input
                            type="text"
                            id="title"
                            v-model="form.title"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Description</label
                        >
                        <textarea
                            id="description"
                            v-model="form.description"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required
                        ></textarea>
                    </div>

                    <div class="mb-4">
                        <label
                            for="price"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Price</label
                        >
                        <input
                            type="number"
                            id="price"
                            v-model="form.price"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <label
                            for="location"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Location</label
                        >
                        <input
                            type="text"
                            id="location"
                            v-model="form.location"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <label
                            for="category_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Category</label
                        >
                        <select
                            id="category_id"
                            v-model="form.category_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required
                        >
                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label
                            for="images"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Images</label
                        >
                        <input
                            type="file"
                            id="images"
                            @change="handleFileUpload"
                            multiple
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        />
                        <div class="mt-2 flex space-x-4">
                            <img
                                v-for="image in form.images"
                                :key="image.name"
                                :src="URL.createObjectURL(image)"
                                class="h-20 w-20 object-cover rounded-md"
                            />
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg"
                        >
                            Update
                        </button>
                        <button
                            type="button"
                            @click="cancelEdit"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </KalakalLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { ref } from "vue";
import { usePage, Head, router } from "@inertiajs/vue3";
import axiosClient from "@/axiosClient.js";
import KalakalLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import SidebarList from "@/Components/Kalakalkoto/SideBarList.vue";

const { props } = usePage();
const item = ref(props.item);
const categories = ref(props.categories || []);

const form = ref({
    title: item.value.title,
    description: item.value.description,
    price: item.value.price,
    location: item.value.location,
    category_id: item.value.category_id,
    images: [],
});

const handleFileUpload = (event) => {
    form.value.images = Array.from(event.target.files);
};

const updateItem = async () => {
    if (confirm("Are you sure you want to update this item?")) {
        const formData = new FormData();
        formData.append("title", form.value.title);
        formData.append("description", form.value.description);
        formData.append("price", form.value.price);
        formData.append("location", form.value.location);
        formData.append("category_id", form.value.category_id);

        form.value.images.forEach((image, index) => {
            formData.append(`images[${index}]`, image);
        });

        try {
            const response = await axiosClient.post(
                `/kalakalkoto/item/update/${item.value.id}`,
                formData,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );
            if (response.data.redirect) {
                router.visit(response.data.redirect);
            }
        } catch (error) {
            console.error("There was an error updating the item:", error);
        }
    }
};

const cancelEdit = () => {
    router.visit(`/kalakalkoto/item/${item.value.id}`);
};
</script>

<style scoped></style>
