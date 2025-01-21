<template>
	<Head title="Adkoto Create" />

	<AdkotoLayout>
		<div
			class="max-w-4xl mx-auto my-2 h-full overflow-y-auto p-6 bg-white shadow-md rounded-lg scrollbar-thin">
			<h1 class="text-3xl font-bold mb-6">Create New Advertisement</h1>
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

				<!-- Image Previews -->
				<div
					v-if="form.images.length"
					class="mt-4">
					<h3 class="text-lg font-medium text-gray-700">Image Preview</h3>
					<div class="mt-2 flex flex-wrap space-x-2">
						<div
							v-for="(image, index) in imagePreviews"
							:key="index"
							class="relative">
							<img
								:src="image.url"
								alt="Preview"
								class="w-24 h-24 object-cover rounded-md shadow-sm" />
							<button
								@click="removeImage(index)"
								class="absolute top-0 right-0 bg-red-600 text-white rounded-full p-1 shadow-md">
								&times;
							</button>
						</div>
					</div>
				</div>

				<!-- Submit Button -->
				<button
					type="submit"
					class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 transition duration-300 ease-in-out">
					Submit
				</button>
			</form>
		</div>
	</AdkotoLayout>
	<UpdateProfileReminder />
</template>

<script setup>
import { ref, watch } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AdkotoLayout from "@/Layouts/AdkotoLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import { useToast } from "vue-toastification";

const props = defineProps({
	categories: Array,
});

const subCategories = ref([]);
const toast = useToast();
const imagePreviews = ref([]);

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
	const selectedCategory = props.categories.find((category) => category.id === form.category_id);
	subCategories.value = selectedCategory ? selectedCategory.sub_categories : [];
};

const handleFileUpload = (event) => {
	const files = Array.from(event.target.files);
	form.images.push(...files);

	imagePreviews.value.push(
		...files.map((file) => ({
			file,
			url: URL.createObjectURL(file),
		}))
	);
};

const removeImage = (index) => {
	form.images.splice(index, 1);
	URL.revokeObjectURL(imagePreviews.value[index].url);
	imagePreviews.value.splice(index, 1);
};

const submitForm = () => {
	form.post("/adkoto", {
		onSuccess: () => {
			toast.success("Advertisement created successfully!");
			resetForm();
		},
		onError: () => {
			toast.error("There was an error creating the advertisement.");
		},
	});
};

watch(() => form.category_id, fetchSubCategories);
</script>
