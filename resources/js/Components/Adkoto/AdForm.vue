<template>
    <form @submit.prevent="submitForm" enctype="multipart/form-data">
      <div class="mb-4">
        <label for="title" class="block text-gray-700 dark:text-gray-300 mb-2">Title</label>
        <input type="text" id="title" v-model="form.title" class="w-full p-2 border rounded" />
        <p v-if="form.errors.title" class="text-red-500 text-xs italic">{{ form.errors.title }}</p>
      </div>
      <div class="mb-4">
        <label for="description" class="block text-gray-700 dark:text-gray-300 mb-2">Description</label>
        <textarea id="description" v-model="form.description" class="w-full p-2 border rounded"></textarea>
        <p v-if="form.errors.description" class="text-red-500 text-xs italic">{{ form.errors.description }}</p>
      </div>
      <div class="mb-4">
        <label for="price" class="block text-gray-700 dark:text-gray-300 mb-2">Price</label>
        <input type="text" id="price" v-model="form.price" class="w-full p-2 border rounded" />
        <p v-if="form.errors.price" class="text-red-500 text-xs italic">{{ form.errors.price }}</p>
      </div>
      <div class="mb-4">
        <label for="category" class="block text-gray-700 dark:text-gray-300 mb-2">Category</label>
        <select id="category" v-model="form.category_id" class="w-full p-2 border rounded">
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
        <p v-if="form.errors.category_id" class="text-red-500 text-xs italic">{{ form.errors.category_id }}</p>
      </div>
      <div class="mb-4">
        <label for="location" class="block text-gray-700 dark:text-gray-300 mb-2">Location</label>
        <input type="text" id="location" v-model="form.location" class="w-full p-2 border rounded" />
        <p v-if="form.errors.location" class="text-red-500 text-xs italic">{{ form.errors.location }}</p>
      </div>
      <div class="mb-4">
        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
        <input type="file" name="image" id="image" @change="handleFileChange" class="mt-1 block w-full">
        <p v-if="form.errors.image" class="text-red-500 text-xs italic">{{ form.errors.image }}</p>
      </div>
      <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
    </form>
  </template>

  <script setup>
  import { useForm } from '@inertiajs/vue3'
  import { defineProps } from 'vue'

  const props = defineProps({
    categories: Array // Define props to receive categories from parent component
  });

  const form = useForm({
    title: '',
    description: '',
    price: '',
    category_id: '',
    location: '',
    image: null
  });

  // Handle file change event
  const handleFileChange = (event) => {
    form.image = event.target.files[0];
  };

  // Handle form submission
  const submitForm = () => {
    form.post(route('adkoto.store'), {
      onSuccess: () => {
        console.log('Form submitted successfully');
        // Optionally redirect or show success message
      },
      onError: (errors) => {
        console.error('Form submission error:', errors);
        // Handle validation errors or other errors
      }
    });
  };
  </script>
