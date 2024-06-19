<template>
    <div>
      <AdForm :ad="ad" :categories="categories" :isEdit="true" @submit="updateAd" />
    </div>
  </template>

  <script setup>
  import { useForm, router } from '@inertiajs/vue3'
  import AdForm from '@/Components/Adkoto/AdForm.vue'

  const props = defineProps({
    ad: Object,
    categories: Array
  })

  const form = useForm({ ...props.ad })

  function updateAd(form) {
    router.put(route('adkoto.update', props.ad.id), form)
  }
  </script>
