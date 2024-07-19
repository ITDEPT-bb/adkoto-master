<template>
      <div class="p-4 h-full overflow-y-auto rounded-lg dark:border-gray-700">
        <div class="grid lg:grid-cols-3 gap-4 mb-4">
          <Link v-for="item in items" :key="item.id" :href="route('kalakalkoto.item.show', item.id)">
            <div class="flex flex-col items-start p-4 max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg">
              <img :src="item.images[0]?.url || 'https://via.placeholder.com/150'" alt="Product Image" class="w-full h-40 object-cover rounded-t-lg" />
              <div class="p-4 flex flex-col items-start space-y-2">
                <div class="flex flex-col justify-between w-full">
                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">₱ {{ item.price }}</p>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ item.title }}</h2>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ item.location }}</p>
              </div>
            </div>
          </Link>
        </div>
      </div>
  </template>


  <script setup>
  import { Link } from '@inertiajs/vue3';
  import { ref } from 'vue';

  defineProps(['items']);
  </script>

  <style scoped></style>
