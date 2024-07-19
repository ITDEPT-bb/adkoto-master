<template>
    <Head title="Create Item" />

    <KalakalLayout>
        <div class="p-4 max-w-2xl mx-auto">
            <h1 class="text-2xl font-bold mb-4">Create New Item</h1>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" v-model="form.title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="form.description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <input type="number" v-model="form.price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                </div>
                <div class="mb-4">
                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                    <input type="text" v-model="form.location" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                </div>
                <div class="mb-4">
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select v-model="form.category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="images" class="block text-sm font-medium text-gray-700">Images</label>
                    <input type="file" multiple @change="handleFileUpload" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                </div>
                <div class="mt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
                </div>
            </form>
        </div>
    </KalakalLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import KalakalLayout from '@/Layouts/KalakalLayout.vue';
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import { Head } from '@inertiajs/vue3';

const { props } = usePage();
const categories = ref(props.categories || []);

const form = useForm({
    title: '',
    description: '',
    price: '',
    location: '',
    category_id: '',
    images: []
});

function handleFileUpload(event) {
    form.images = Array.from(event.target.files);
}

function submit() {
    form.post(route('kalakalkoto.item.store'));
}
</script>
