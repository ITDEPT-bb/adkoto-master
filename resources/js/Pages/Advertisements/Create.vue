<template>
    <div>
        <h1>Create New Advertisement</h1>
        <form @submit.prevent="submitForm">
            <div>
                <label for="category_id">Category</label>
                <select v-model="form.category_id" @change="fetchSubCategories">
                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <div>
                <label for="sub_category_id">Sub Category</label>
                <select v-model="form.sub_category_id">
                    <option
                        v-for="subCategory in subCategories"
                        :key="subCategory.id"
                        :value="subCategory.id"
                    >
                        {{ subCategory.name }}
                    </option>
                </select>
            </div>

            <div>
                <label for="title">Title</label>
                <input type="text" v-model="form.title" required />
            </div>

            <div>
                <label for="description">Description</label>
                <textarea v-model="form.description" required></textarea>
            </div>

            <div>
                <label for="price">Price</label>
                <input type="number" v-model="form.price" required />
            </div>

            <div>
                <label for="location">Location</label>
                <input type="text" v-model="form.location" required />
            </div>

            <div>
                <label for="images">Images</label>
                <input type="file" @change="handleFileUpload" multiple />
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>
</template>

<script setup>
import { reactive, ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    categories: Array,
});

const subCategories = ref([]);

const form = useForm({
    category_id: "",
    sub_category_id: "",
    title: "",
    description: "",
    price: "",
    location: "",
    images: [],
});

const fetchSubCategories = () => {
    const selectedCategory = props.categories.find(
        (category) => category.id === form.category_id
    );
    subCategories.value = selectedCategory
        ? selectedCategory.sub_categories
        : [];
};

const handleFileUpload = (event) => {
    form.images = Array.from(event.target.files);
};

const submitForm = () => {
    form.post("/advertisements", {
        onSuccess: () => {
            // Handle success (e.g., redirect or show a message)
        },
        onError: () => {
            // Handle error (e.g., show validation errors)
        },
    });
};

watch(() => form.category_id, fetchSubCategories);
</script>
