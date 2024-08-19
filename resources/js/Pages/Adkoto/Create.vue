<template>
    <Head title="Adkoto Create" />

    <AdkotoLayout>
        <div
            class="max-w-4xl mx-auto my-2 h-full overflow-y-auto p-6 bg-white shadow-md rounded-lg"
        >
            <h1 class="text-3xl font-bold mb-6">Create New Advertisement</h1>

            <!-- Notifications -->
            <div
                v-if="notification.message"
                :class="notificationClass"
                class="p-4 mb-4 border rounded"
            >
                <p>{{ notification.message }}</p>
            </div>

            <!-- Wizard Steps -->
            <div v-if="step === 1">
                <!-- Step 1: Select Category and Sub-Category -->
                <form @submit.prevent="nextStep" class="space-y-6">
                    <!-- Category -->
                    <div>
                        <label
                            for="category_id"
                            class="block text-sm font-medium text-gray-700"
                            >Category</label
                        >
                        <select
                            v-model="form.category_id"
                            @change="fetchSubCategories"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        >
                            <option value="" disabled>Select a category</option>
                            <option
                                v-for="category in props.categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                        <span
                            v-if="errors.category_id"
                            class="text-red-600 text-sm"
                            >{{ errors.category_id }}</span
                        >
                    </div>

                    <!-- Sub Category -->
                    <div>
                        <label
                            for="sub_category_id"
                            class="block text-sm font-medium text-gray-700"
                            >Sub Category</label
                        >
                        <select
                            v-model="form.sub_category_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        >
                            <option value="" disabled>
                                Select a sub category
                            </option>
                            <option
                                v-for="subCategory in subCategories"
                                :key="subCategory.id"
                                :value="subCategory.id"
                            >
                                {{ subCategory.name }}
                            </option>
                        </select>
                        <span
                            v-if="errors.sub_category_id"
                            class="text-red-600 text-sm"
                            >{{ errors.sub_category_id }}</span
                        >
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-6">
                        <button
                            type="button"
                            @click="nextStep"
                            :disabled="!isStep1Valid"
                            class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 transition duration-300 ease-in-out"
                        >
                            Next
                        </button>
                    </div>
                </form>
            </div>

            <div v-if="step === 2">
                <!-- Step 2: Enter Advertisement Information -->
                <form @submit.prevent="nextStep" class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label
                            for="title"
                            class="block text-sm font-medium text-gray-700"
                            >Title</label
                        >
                        <input
                            type="text"
                            v-model="form.title"
                            required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        />
                        <span
                            v-if="errors.title"
                            class="text-red-600 text-sm"
                            >{{ errors.title }}</span
                        >
                    </div>

                    <!-- Description -->
                    <div>
                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700"
                            >Description</label
                        >
                        <textarea
                            v-model="form.description"
                            required
                            rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        ></textarea>
                        <span
                            v-if="errors.description"
                            class="text-red-600 text-sm"
                            >{{ errors.description }}</span
                        >
                    </div>

                    <!-- Price -->
                    <div>
                        <label
                            for="price"
                            class="block text-sm font-medium text-gray-700"
                            >Price</label
                        >
                        <input
                            type="number"
                            v-model="form.price"
                            required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        />
                        <span
                            v-if="errors.price"
                            class="text-red-600 text-sm"
                            >{{ errors.price }}</span
                        >
                    </div>

                    <!-- Location -->
                    <div>
                        <label
                            for="location"
                            class="block text-sm font-medium text-gray-700"
                            >Location</label
                        >
                        <input
                            type="text"
                            v-model="form.location"
                            required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        />
                        <span
                            v-if="errors.location"
                            class="text-red-600 text-sm"
                            >{{ errors.location }}</span
                        >
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-6">
                        <button
                            type="button"
                            @click="prevStep"
                            class="w-full py-2 px-4 bg-gray-600 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 transition duration-300 ease-in-out"
                        >
                            Previous
                        </button>
                        <button
                            type="button"
                            @click="nextStep"
                            :disabled="!isStep2Valid"
                            class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 transition duration-300 ease-in-out"
                        >
                            Next
                        </button>
                    </div>
                </form>
            </div>

            <div v-if="step === 3">
                <!-- Step 3: Preview and Submit -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-xl font-semibold">Preview</h2>
                        <div>
                            <p><strong>Title:</strong> {{ form.title }}</p>
                            <p>
                                <strong>Description:</strong>
                                {{ form.description }}
                            </p>
                            <p><strong>Price:</strong> {{ form.price }}</p>
                            <p>
                                <strong>Location:</strong> {{ form.location }}
                            </p>
                            <p>
                                <strong>Category:</strong>
                                {{ selectedCategoryName }}
                            </p>
                            <p>
                                <strong>Sub Category:</strong>
                                {{ selectedSubCategoryName }}
                            </p>
                        </div>
                        <div v-if="form.images.length">
                            <h3 class="text-lg font-medium">Images:</h3>
                            <div class="flex space-x-4">
                                <img
                                    v-for="image in form.images"
                                    :key="image.name"
                                    :src="URL.createObjectURL(image)"
                                    alt="Preview"
                                    class="w-32 h-32 object-cover rounded-md"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-6">
                        <button
                            type="button"
                            @click="prevStep"
                            class="w-full py-2 px-4 bg-gray-600 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 transition duration-300 ease-in-out"
                        >
                            Previous
                        </button>
                        <button
                            type="button"
                            @click="submitForm"
                            class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 transition duration-300 ease-in-out"
                        >
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdkotoLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AdkotoLayout from "@/Layouts/AdkotoLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

const props = defineProps({
    categories: Array,
});

const subCategories = ref([]);
const step = ref(1);
const errors = ref({
    category_id: "",
    sub_category_id: "",
    title: "",
    description: "",
    price: "",
    location: "",
});
const notification = ref({
    message: "",
    type: "", // 'success' or 'error'
});

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
    form.post("/adkoto", {
        onSuccess: () => {
            notification.value = {
                message: "Advertisement created successfully!",
                type: "success",
            };
            setTimeout(() => {
                notification.value.message = "";
            }, 3000);
        },
        onError: (errors) => {
            errors.forEach((error) => {
                const [field, message] = error;
                errors.value[field] = message;
            });
            notification.value = {
                message: "Please fix the errors and try again.",
                type: "error",
            };
            setTimeout(() => {
                notification.value.message = "";
            }, 3000);
        },
    });
};

const isStep1Valid = computed(() => form.category_id && form.sub_category_id);
const isStep2Valid = computed(
    () => form.title && form.description && form.price && form.location
);

const nextStep = () => {
    if (step.value === 1 && isStep1Valid.value) {
        step.value = 2;
    } else if (step.value === 2 && isStep2Valid.value) {
        step.value = 3;
    } else {
        notification.value = {
            message: "Please fill in all required fields.",
            type: "error",
        };
        setTimeout(() => {
            notification.value.message = "";
        }, 3000);
    }
};

const prevStep = () => {
    if (step.value === 2) {
        step.value = 1;
    } else if (step.value === 3) {
        step.value = 2;
    }
};

watch(() => form.category_id, fetchSubCategories);

const selectedCategoryName = computed(() => {
    const category = props.categories.find((c) => c.id === form.category_id);
    return category ? category.name : "";
});

const selectedSubCategoryName = computed(() => {
    const category = props.categories.find((c) => c.id === form.category_id);
    const subCategory = category
        ? category.sub_categories.find((sc) => sc.id === form.sub_category_id)
        : null;
    return subCategory ? subCategory.name : "";
});

const notificationClass = computed(() => {
    return notification.value.type === "success"
        ? "bg-green-100 border-green-500 text-green-700"
        : "bg-red-100 border-red-500 text-red-700";
});
</script>

<style scoped>
/* Add any custom styles if needed */
</style>
