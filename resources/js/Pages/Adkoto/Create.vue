<template>
    <div>
      <AdForm :categories="categories" @submit="submitForm" />
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import AdForm from '@/Components/Adkoto/AdForm.vue';

  const categories = ref([]);

  // Fetch categories on component mount
  onMounted(() => {
    axios.get('/api/categories') // Ensure this matches the route defined in Laravel
      .then(response => {
        categories.value = response.data;
      })
      .catch(error => {
        console.error('Error fetching categories:', error);
        // Handle error as needed
      });
  });

  const submitForm = (formData) => {
    console.log('Form data:', formData);
    // Example: Submit form data using Axios
    axios.post('/adkoto', formData)
      .then(response => {
        console.log('Form submitted successfully:', response.data);
        // Optionally redirect or show success message
      })
      .catch(error => {
        console.error('Form submission error:', error.response.data);
        // Handle validation errors or other errors
      });
  };
  </script>
