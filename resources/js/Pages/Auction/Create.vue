<template>
    <Head title="Auction Item Create" />

    <KalakalkotoLayout>
        <div class="h-full overflow-y-auto">
            <div class="max-w-7xl mx-auto p-4">
                <AuctionMenu />
            </div>
            <div
                class="max-w-4xl mx-auto my-2 p-6 bg-white shadow-md rounded-lg"
            >
                <h1 class="text-3xl font-bold mb-6">
                    Create New Item for Auction
                </h1>
                <form @submit.prevent="submitForm" class="space-y-6 mx-auto">
                    <div class="flex justify-between gap-6 items-center">
                        <!-- Name -->
                        <div class="w-full">
                            <label
                                for="name"
                                class="block text-sm font-medium text-gray-700"
                                >Name of the Item</label
                            >
                            <input
                                type="text"
                                v-model="form.name"
                                required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            />
                        </div>

                        <!-- Category -->
                        <div class="w-full">
                            <label
                                for="category_id"
                                class="block text-sm font-medium text-gray-700"
                                >Category</label
                            >
                            <select
                                v-model="form.category_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            >
                                <option value="" disabled>
                                    Select a category
                                </option>
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700"
                            >Description</label
                        >
                        <textarea
                            v-model="form.description"
                            required
                            rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        ></textarea>
                    </div>

                    <div class="flex justify-between gap-6 items-center">
                        <!-- Price -->
                        <div class="w-full">
                            <label
                                for="starting_price"
                                class="block text-sm font-medium text-gray-700"
                                >Price</label
                            >
                            <input
                                type="number"
                                v-model="form.starting_price"
                                required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            />
                        </div>

                        <!-- Bidding Type -->
                        <div class="w-full">
                            <label
                                for="bidding_type"
                                class="block text-sm font-medium text-gray-700"
                                >Type</label
                            >

                            <select
                                v-model="form.bidding_type"
                                id="bidding_type"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                required
                            >
                                <option disabled value="">Select One</option>
                                <option value="normal">Normal</option>
                                <option value="live">Live</option>
                            </select>
                        </div>
                    </div>

                    <!-- Location -->
                    <div>
                        <label
                            for="location"
                            class="block text-sm font-medium text-gray-700"
                            >Location</label
                        >
                        <input
                            type="text"
                            v-model="form.location"
                            required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        />
                    </div>

                    <div>
                        <label
                            for="auction_ends_at"
                            class="block text-sm font-medium text-gray-700"
                            >Auction end Date</label
                        >
                        <flat-pickr
                            id="auction_ends_at"
                            v-model="form.auction_ends_at"
                            type="date"
                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 focus:border-red-500 focus:ring dark:focus:border-red-600 focus:ring-red-500 dark:focus:ring-red-600 focus:ring-opacity-50"
                            required
                            autocomplete="auction_ends_at"
                            placeholder="Select date"
                            :config="config"
                        />
                    </div>

                    <!-- Images -->
                    <div>
                        <label
                            for="images"
                            class="block text-sm font-medium text-gray-700"
                            >Images</label
                        >
                        <input
                            type="file"
                            @change="handleFileUpload"
                            multiple
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        />
                    </div>

                    <!-- Image Previews -->
                    <div v-if="imagePreviews.length" class="mt-4">
                        <h3 class="text-lg font-medium text-gray-700">
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
                                    class="w-24 h-24 object-cover rounded-md shadow-sm"
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
    </KalakalkotoLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { ref, watch, watchEffect } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import KalakalkotoLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import { useToast } from "vue-toastification";

import AuctionMenu from "@/Components/Auction/AuctionMenu.vue";

import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
const date = ref(null);

const config = ref({
    altFormat: "M j, Y",
    altInput: true,
    dateFormat: "Y-m-d",
    // maxDate: "today",
    minDate: "today",
});

const props = defineProps({
    categories: Array,
});

const toast = useToast();
const imagePreviews = ref([]);

const form = useForm({
    name: "",
    category_id: "",
    description: "",
    location: "",
    starting_price: "",
    bidding_type: "",
    auction_ends_at: "",
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
    console.log(form);
    formData.append("name", form.name);
    formData.append("category_id", form.category_id);
    formData.append("description", form.description);
    formData.append("location", form.location);
    formData.append("starting_price", form.starting_price);
    formData.append("bidding_type", form.bidding_type);
    formData.append("auction_ends_at", form.auction_ends_at);

    form.images.forEach((image) => {
        formData.append("attachments[]", image);
    });

    axios
        .post("/auction", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then(() => {
            toast.success("Item created successfully!");
            resetForm();
            isLoading.value = false;
        })
        .catch((errors) => {
            toast.error("There was an error creating the item.");
            isLoading.value = false;
        });
};

const resetForm = () => {
    form.reset();
    imagePreviews.value = [];
};

watchEffect(() => form.category_id);
</script>
