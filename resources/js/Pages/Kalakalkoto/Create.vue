<template>
    <Head title="Kalakalkoto Create" />

    <AuthenticatedLayout>
        <div class="h-full overflow-y-auto scrollbar-thin">
            <div class="max-w-7xl mx-auto p-4">
                <KalakalMenu />
            </div>
            <div
                class="max-w-4xl mx-auto my-2 p-6 bg-white shadow-md rounded-lg dark:bg-slate-950 dark:text-white"
            >
                <h1 class="text-3xl font-bold mb-6">Create New Listing</h1>
                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700 dark:text-white"
                            >Name of the Product</label
                        >
                        <input
                            type="text"
                            v-model="form.name"
                            required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-slate-950 dark:text-white dark:border-gray-700"
                            placeholder="Enter the name of the product"
                        />
                    </div>

                    <!-- Category -->
                    <div>
                        <label
                            for="category_id"
                            class="block text-sm font-medium text-gray-700 dark:text-white"
                            >Category</label
                        >
                        <select
                            v-model="form.category_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-slate-950 dark:text-white dark:border-gray-700"
                            placeholder="Enter the name of the product"
                        >
                            <option value="" disabled>Select a category</option>
                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-white"
                            >Description</label
                        >
                        <textarea
                            v-model="form.description"
                            required
                            rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-slate-950 dark:text-white dark:border-gray-700"
                            placeholder="Enter the description of the product"
                        ></textarea>
                    </div>

                    <!-- Price -->
                    <div>
                        <label
                            for="price"
                            class="block text-sm font-medium text-gray-700 dark:text-white"
                            >Price</label
                        >
                        <input
                            type="number"
                            v-model="form.price"
                            required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-slate-950 dark:text-white dark:border-gray-700"
                            placeholder="Enter the price of the product"
                        />
                    </div>

                    <!-- Location -->
                    <div>
                        <label
                            for="location"
                            class="block text-sm font-medium text-gray-700 dark:text-white"
                            >Location</label
                        >
                        <input
                            type="text"
                            v-model="form.location"
                            required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-slate-950 dark:text-white dark:border-gray-700"
                            placeholder="Enter the location of the seller"
                        />
                    </div>

                    <!-- Images -->
                    <div>
                        <label
                            for="images"
                            class="block text-sm font-medium text-gray-700 dark:text-white"
                            >Images</label
                        >
                        <input
                            type="file"
                            @change="handleFileUpload"
                            multiple
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-slate-950 dark:text-white dark:border-gray-700"
                            placeholder="Enter the images of the product"
                        />
                    </div>

                    <!-- Image Previews -->
                    <div v-if="imagePreviews.length" class="mt-4">
                        <h3
                            class="text-lg font-medium text-gray-700 dark:text-white"
                        >
                            Image Previews
                        </h3>
                        <div class="mt-2 flex flex-wrap space-x-2">
                            <div
                                v-for="(image, index) in imagePreviews"
                                :key="index"
                                class="relative"
                            >
                                <img
                                    :src="image.url"
                                    alt="Preview"
                                    class="w-24 h-24 object-cover rounded-md shadow-sm dark:bg-slate-950 dark:text-white dark:border-gray-700"
                                    placeholder="Enter the name of the product"
                                />
                                <button
                                    @click="removeImage(index)"
                                    class="absolute top-0 right-0 bg-red-600 text-white rounded-full p-1 shadow-md"
                                >
                                    &times;
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="isLoading"
                        :class="{
                            'w-full py-2 px-4 bg-blue-300 text-white font-semibold rounded-md shadow-md hover:bg-blue-300 transition duration-300 ease-in-out cursor-not-allowed':
                                isLoading,
                            'w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 transition duration-300 ease-in-out':
                                !isLoading,
                        }"
                    >
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { ref, watch, watchEffect } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import { useToast } from "vue-toastification";

import KalakalMenu from "@/Components/Kalakalkoto/KalakalMenu.vue";

const props = defineProps({
    categories: Array,
});

const toast = useToast();
const imagePreviews = ref([]);

const form = useForm({
    category_id: "",
    name: "",
    description: "",
    price: "",
    location: "",
    images: [],
});

const handleFileUpload = (event) => {
    const files = Array.from(event.target.files);
    form.images.push(...files);

    imagePreviews.value.push(
        ...files.map((file) => ({
            file,
            url: URL.createObjectURL(file),
        }))
    );
};

const removeImage = (index) => {
    form.images.splice(index, 1);
    URL.revokeObjectURL(imagePreviews.value[index].url);
    imagePreviews.value.splice(index, 1);
};

const isLoading = ref(false);
const submitForm = () => {
    isLoading.value = true;
    const formData = new FormData();
    formData.append("name", form.name);
    formData.append("description", form.description);
    formData.append("price", form.price);
    formData.append("location", form.location);
    formData.append("category_id", form.category_id);

    form.images.forEach((image) => {
        formData.append("attachments[]", image);
    });

    axios
        .post("/kalakalkoto", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then(() => {
            toast.success("Listing created successfully!");
            resetForm();
            isLoading.value = false;
        })
        .catch(() => {
            toast.error("There was an error creating the listing.");
            isLoading.value = false;
        });
};

const resetForm = () => {
    form.reset();
    imagePreviews.value = [];
};

watchEffect(() => form.category_id);
</script>
