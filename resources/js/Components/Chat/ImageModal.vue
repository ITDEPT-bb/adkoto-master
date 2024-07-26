<template>
    <div v-if="isVisible" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" @wheel="handleWheel">
      <!-- Close button positioned at the top-right of the screen -->
      <button @click="closeModal" class="absolute top-4 right-4 text-white text-lg bg-gray-800 px-2 rounded-md z-50">
        &times;
      </button>

      <!-- Image container with zoom support -->
      <div class="relative">
        <img :src="src" alt="Full Screen Image" class="max-w-screen max-h-screen object-contain" :style="{ transform: `scale(${zoomLevel})` }" />
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';

  const props = defineProps(['src', 'isVisible']);
  const emit = defineEmits(['update:isVisible']);

  const zoomLevel = ref(1); // Initial zoom level

  const closeModal = () => {
    emit('update:isVisible', false);
  };

  // Handle mouse wheel event for zooming
  const handleWheel = (event) => {
    event.preventDefault();
    if (event.deltaY < 0) {
      zoomIn();
    } else if (event.deltaY > 0) {
      zoomOut();
    }
  };

  // Zoom in
  const zoomIn = () => {
    zoomLevel.value += 0.1;
  };

  // Zoom out
  const zoomOut = () => {
    if (zoomLevel.value > 0.1) {
      zoomLevel.value -= 0.1;
    }
  };
  </script>

  <style scoped>
  /* Add any additional styles if needed */
  </style>
