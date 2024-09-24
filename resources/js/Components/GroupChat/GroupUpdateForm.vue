<script setup>
import TextInput from "@/Components/TextInput.vue";
import InputTextarea from "@/Components/InputTextarea.vue";
import { ref } from "vue";

const props = defineProps({
    form: Object,
});

const imagePreview = ref(form.image_url || null);

function handleImageChange(event) {
    const file = event.target.files[0];
    if (file) {
        form.image = file;

        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>

<template>
    <div class="space-y-6">
        <!-- Group Chat Name -->
        <div class="mb-3 dark:text-gray-100">
            <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
            >
                Group Chat Name
            </label>
            <TextInput
                type="text"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                v-model="form.name"
                required
            />
        </div>

        <!-- Group Chat Description -->
        <div class="mb-3 dark:text-gray-100">
            <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
            >
                Group Chat Description
            </label>
            <InputTextarea
                v-model="form.description"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            ></InputTextarea>
        </div>

        <!-- Group Chat Image -->
        <div class="mb-3 dark:text-gray-100">
            <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
            >
                Group Chat Image
            </label>
            <input
                type="file"
                accept="image/*"
                @change="handleImageChange"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 focus:outline-none"
            />
        </div>

        <!-- Image Preview -->
        <div v-if="imagePreview" class="mb-3">
            <img
                :src="imagePreview"
                alt="Image Preview"
                class="w-32 h-32 rounded-full object-cover"
            />
        </div>
    </div>
</template>
