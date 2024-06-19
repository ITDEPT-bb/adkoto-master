<template>
    <AuthenticatedLayout>
      <div class="max-w-7xl mx-auto p-4 h-full overflow-y-auto">
        <!-- Create New Ad Button -->
        <Link
          :href="route('adkoto.create')"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4 block"
        >
          Create New Ad
        </Link>

        <!-- Categories Section -->
        <div class="mt-8">
          <h2 class="text-xl font-bold mb-4">Categories</h2>
          <ul class="flex flex-wrap gap-2">
            <li v-for="category in categories" :key="category.id">
              <button
                @click="selectCategory(category.id)"
                :class="`px-4 py-2 rounded-lg font-semibold ${selectedCategory === category.id ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'}`"
              >
                {{ category.name }}
              </button>
            </li>
          </ul>
        </div>

        <!-- Advertisements Section -->
        <div class="mt-8">
          <h1 class="text-2xl font-bold mb-4">
            {{ selectedCategory ? getCategoryName(selectedCategory) : 'Advertisements' }}
          </h1>

          <!-- Show Ads or No Ads Found Message -->
          <div v-if="filteredAds.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
            <AdCard v-for="ad in filteredAds" :key="ad.id" :ad="ad" />
          </div>
          <div v-else class="text-gray-600 mt-4">
            No ads found in this category.
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>

  <script setup>
  import { defineProps, ref, computed } from 'vue';
  import { Link } from '@inertiajs/vue3';
  import AdCard from '@/Components/Adkoto/AdCard.vue';
  import AuthenticatedLayout from '@/Layouts/AdkotoLayout.vue';

  const props = defineProps({
    ads: Array,
    categories: Array,
  });

  const selectedCategory = ref(null);

  const filteredAds = computed(() => {
    if (!selectedCategory.value) {
      return props.ads;
    } else {
      return props.ads.filter(ad => ad.category_id === selectedCategory.value);
    }
  });

  function selectCategory(categoryId) {
    selectedCategory.value = categoryId === selectedCategory.value ? null : categoryId;
  }

  function getCategoryName(categoryId) {
    const category = props.categories.find(cat => cat.id === categoryId);
    return category ? category.name : '';
  }

  </script>
