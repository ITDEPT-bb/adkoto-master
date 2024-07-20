<template>
    <div
        class="p-4 my-2 h-full overflow-y-auto max-w-3xl mx-auto bg-white rounded-lg shadow-lg"
    >
        <h1 class="text-3xl font-semibold mb-6 text-gray-800">
            Create New Listing
        </h1>
        <form @submit.prevent="submit">
            <div class="mb-6">
                <label
                    for="title"
                    class="block text-sm font-medium text-gray-700"
                    >Title</label
                >
                <input
                    type="text"
                    v-model="form.title"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="Enter the item title"
                />
            </div>
            <div class="mb-6">
                <label
                    for="description"
                    class="block text-sm font-medium text-gray-700"
                    >Description</label
                >
                <textarea
                    v-model="form.description"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="Enter the item description"
                    rows="4"
                ></textarea>
            </div>
            <div class="mb-6">
                <label
                    for="price"
                    class="block text-sm font-medium text-gray-700"
                    >Price</label
                >
                <input
                    type="number"
                    v-model="form.price"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="Enter the item price"
                />
            </div>
            <div class="mb-6">
                <label
                    for="location"
                    class="block text-sm font-medium text-gray-700"
                    >Location</label
                >
                <input
                    type="text"
                    v-model="form.location"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="Enter the item location"
                />
            </div>
            <div class="mb-6">
                <label
                    for="category_id"
                    class="block text-sm font-medium text-gray-700"
                    >Category</label
                >
                <select
                    v-model="form.category_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
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
            <div class="mb-6">
                <label
                    for="images"
                    class="block text-sm font-medium text-gray-700"
                    >Images</label
                >
                <input
                    type="file"
                    multiple
                    @change="handleFileUpload"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
            </div>
            <div class="mb-6" v-if="previewImages.length > 0">
                <label class="block text-sm font-medium text-gray-700"
                    >Image Preview</label
                >
                <div class="flex flex-wrap gap-4 mt-2">
                    <div
                        v-for="(image, index) in previewImages"
                        :key="index"
                        class="w-32 h-32 border border-gray-300 rounded-md overflow-hidden cursor-pointer"
                        @click="openPreview(image)"
                    >
                        <img :src="image" class="object-cover w-full h-full" />
                    </div>
                </div>
            </div>
            <div class="mt-8">
                <button
                    type="submit"
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Create
                </button>
            </div>
        </form>

        <transition name="modal">
            <div
                v-if="previewImage"
                class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
            >
                <div
                    class="bg-white p-5 rounded-lg shadow-lg relative max-w-md"
                >
                    <button
                        @click="closePreview"
                        class="absolute top-2 right-2 text-gray-900 text-xl"
                    >
                        &times;
                    </button>
                    <img
                        :src="previewImage"
                        class="max-w-full max-h-80 object-contain"
                    />
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";

const { props } = usePage();
const categories = ref(props.categories || []);
const previewImages = ref([]);
const previewImage = ref(null);

const form = useForm({
    title: "",
    description: "",
    price: "",
    location: "",
    category_id: "",
    images: [],
});

function handleFileUpload(event) {
    const files = Array.from(event.target.files);
    form.images = files;

    previewImages.value = files.map((file) => URL.createObjectURL(file));
}

function openPreview(image) {
    previewImage.value = image;
}

function closePreview() {
    previewImage.value = null;
}

function submit() {
    form.post(route("kalakalkoto.item.store"));
}
</script>

<style>
.modal-enter-active, .modal-leave-active {
  transition: opacity 0.2s, transform 0.2s;
}
.modal-enter, .modal-leave-to {
  opacity: 0;
  transform: scale(0.7);
}
</style>
