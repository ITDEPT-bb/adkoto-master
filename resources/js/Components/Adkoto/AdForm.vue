<template>
    <div>
        <h2 class="text-2xl font-bold mb-4 text-center">
            Create New Advertisement
        </h2>
        <form
            @submit.prevent="submitForm"
            enctype="multipart/form-data"
            class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md"
        >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label
                        for="title"
                        class="block text-gray-700 dark:text-gray-300 mb-2"
                        >Title</label
                    >
                    <input
                        type="text"
                        id="title"
                        v-model="form.title"
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <p
                        v-if="form.errors.title"
                        class="text-red-500 text-xs italic mt-1"
                    >
                        {{ form.errors.title }}
                    </p>
                </div>
                <div class="mb-4">
                    <label
                        for="category"
                        class="block text-gray-700 dark:text-gray-300 mb-2"
                        >Category</label
                    >
                    <select
                        id="category"
                        v-model="form.category_id"
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                    <p
                        v-if="form.errors.category_id"
                        class="text-red-500 text-xs italic mt-1"
                    >
                        {{ form.errors.category_id }}
                    </p>
                </div>
                <div class="mb-4 md:col-span-2">
                    <label
                        for="description"
                        class="block text-gray-700 dark:text-gray-300 mb-2"
                        >Description</label
                    >
                    <textarea
                        id="description"
                        v-model="form.description"
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    ></textarea>
                    <p
                        v-if="form.errors.description"
                        class="text-red-500 text-xs italic mt-1"
                    >
                        {{ form.errors.description }}
                    </p>
                </div>
                <div class="mb-4">
                    <label
                        for="price"
                        class="block text-gray-700 dark:text-gray-300 mb-2"
                        >Price</label
                    >
                    <input
                        type="text"
                        id="price"
                        v-model="form.price"
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <p
                        v-if="form.errors.price"
                        class="text-red-500 text-xs italic mt-1"
                    >
                        {{ form.errors.price }}
                    </p>
                </div>
                <div class="mb-4">
                    <label
                        for="location"
                        class="block text-gray-700 dark:text-gray-300 mb-2"
                        >Location</label
                    >
                    <input
                        type="text"
                        id="location"
                        v-model="form.location"
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <p
                        v-if="form.errors.location"
                        class="text-red-500 text-xs italic mt-1"
                    >
                        {{ form.errors.location }}
                    </p>
                </div>
                <div class="mb-4 md:col-span-2">
                    <label
                        for="image"
                        class="block text-gray-700 dark:text-gray-300 mb-2"
                        >Image</label
                    >
                    <input
                        type="file"
                        name="image"
                        id="image"
                        @change="handleFileChange"
                        class="mt-1 block w-full text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <p
                        v-if="form.errors.image"
                        class="text-red-500 text-xs italic mt-1"
                    >
                        {{ form.errors.image }}
                    </p>
                    <img
                        v-if="imageUrl"
                        :src="imageUrl"
                        alt="Image Preview"
                        class="mt-2 w-full rounded-lg border border-gray-300"
                    />
                </div>
            </div>
            <button
                type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 w-full mt-4"
            >
                Submit
            </button>
        </form>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { defineProps, ref } from "vue";

const props = defineProps({
    categories: Array,
});

const form = useForm({
    title: "",
    description: "",
    price: "",
    category_id: "",
    location: "",
    image: null,
});

const imageUrl = ref(null);

// Handle file change event
const handleFileChange = (event) => {
    const file = event.target.files[0];
    form.image = file;

    // Display image preview
    const reader = new FileReader();
    reader.onload = (e) => {
        imageUrl.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

// Handle form submission
const submitForm = () => {
    form.post(route("adkoto.store"), {
        onSuccess: () => {
            console.log("Form submitted successfully");
            // Optionally redirect or show success message
        },
        onError: (errors) => {
            console.error("Form submission error:", errors);
            // Handle validation errors or other errors
        },
    });
};
</script>
