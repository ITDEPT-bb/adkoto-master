<template>
	<Head title="Edit Advertisement" />

	<AuthenticatedLayout>
		<div
			class="max-w-4xl mx-auto h-full overflow-y-auto p-6 bg-white shadow-md rounded-lg scrollbar-thin">
			<h1 class="text-3xl font-bold mb-6">Edit Advertisement</h1>
			<form
				@submit.prevent="submitForm"
				class="space-y-6">
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
						class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
				</div>

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
						class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
						<option
							value=""
							disabled>
							Select a category
						</option>
						<option
							v-for="category in categories"
							:key="category.id"
							:value="category.id">
							{{ category.name }}
						</option>
					</select>
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
						class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
						<option
							value=""
							disabled>
							Select a sub category
						</option>
						<option
							v-for="subCategory in subCategories"
							:key="subCategory.id"
							:value="subCategory.id">
							{{ subCategory.name }}
						</option>
					</select>
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
						class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
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
						class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
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
						class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
				</div>

				<!-- Images -->
				<div>
					<label
						for="images"
						class="block text-sm font-medium text-gray-700"
						>Images</label
					>
					<input
						type="file"
						@change="handleFileUpload"
						multiple
						class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
				</div>

				<!-- Submit Button -->
				<button
					type="submit"
					class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 transition duration-300 ease-in-out">
					Update
				</button>
			</form>
		</div>
	</AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useToast } from "vue-toastification";

const props = defineProps({
	advertisement: Object,
	categories: Array,
});

const subCategories = ref([]);
const toast = useToast();

const form = useForm({
	id: props.advertisement.id,
	category_id: props.advertisement.category_id,
	sub_category_id: props.advertisement.sub_category_id,
	title: props.advertisement.title,
	description: props.advertisement.description,
	price: props.advertisement.price,
	location: props.advertisement.location,
	images: [],
});

const fetchSubCategories = () => {
	const selectedCategory = props.categories.find((category) => category.id === form.category_id);
	subCategories.value = selectedCategory ? selectedCategory.sub_categories : [];
};

const handleFileUpload = (event) => {
	form.images = Array.from(event.target.files);
};

const submitForm = () => {
	form.put(`/adkoto/${form.id}`, {
		onSuccess: () => {
			toast.success("Advertisement updated successfully!");
		},
		onError: () => {
			toast.error("There was an error updating the advertisement.");
		},
	});
};

onMounted(fetchSubCategories);
</script>
