<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import { ref } from "vue";
import { Cropper } from "vue-advanced-cropper";
import "vue-advanced-cropper/dist/style.css";
import axiosClient from "@/axiosClient.js";
import { useToast } from "vue-toastification";

// Local refs
const img = ref(null);
const fileInput = ref(null);
const croppedImage = ref(null);
const toast = useToast();
const isLoading = ref(false);
const shareCover = ref(false);

const props = defineProps({
	group: {
		type: Object,
	},
});

// const groupId = group.id;
const group = usePage().props.group;

// Function to handle image upload
const handleImageUpload = (event) => {
	const file = event.target.files[0];
	if (file) {
		const reader = new FileReader();
		reader.onload = (e) => {
			img.value = e.target.result;
		};
		reader.readAsDataURL(file);
	}
};

// Function to resize the image to match the desired dimensions
const resizeImage = (dataURL, width, height) => {
	return new Promise((resolve) => {
		const img = new Image();
		img.src = dataURL;
		img.onload = () => {
			const canvas = document.createElement("canvas");
			const ctx = canvas.getContext("2d");
			canvas.width = width;
			canvas.height = height;
			ctx.drawImage(img, 0, 0, width, height);
			resolve(canvas.toDataURL());
		};
	});
};

const cropAndSubmit = async () => {
	isLoading.value = true;
	const canvas = croppedImage.value.getCanvas();
	if (canvas) {
		const croppedDataURL = canvas.toDataURL();

		const resizedDataURL = await resizeImage(croppedDataURL, 1200, 400);
		const blob = await (await fetch(resizedDataURL)).blob();
		const formData = new FormData();
		formData.append("cover", blob, "cover.jpg");
		formData.append("share_cover", shareCover.value ? "1" : "0");

		try {
			const groupId = group.id;
			const response = await axiosClient.post(`/group/${groupId}/update-cover`, formData, {
				headers: {
					"Content-Type": "multipart/form-data",
				},
			});

			if (response.status === 200) {
				toast.success("Cover image has been updated successfully!");
				// window.location.href = "/";
				window.location.href = response.data.redirect;
			}
		} catch (error) {
			console.error("Error uploading image:", error);
			toast.error("There was an error updating your cover image.");
		} finally {
			isLoading.value = false;
		}
	}
};

const reselectImage = () => {
	img.value = null;
	fileInput.value.value = null;
};
</script>

<template>
	<Head title="Update Your Group's Cover Photo" />

	<AuthenticatedLayout>
		<div class="max-w-7xl mx-auto h-full overflow-y-auto p-4 scrollbar-thin">
			<h1 class="text-3xl font-bold text-gray-900 mb-6">Update Your Group's Cover Photo</h1>

			<div
				v-if="!img"
				class="flex items-center justify-center w-full mb-4">
				<label
					for="dropzone-file"
					class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
					<div class="flex flex-col items-center justify-center pt-5 pb-6">
						<svg
							class="w-8 h-8 mb-4 text-gray-500"
							xmlns="http://www.w3.org/2000/svg"
							fill="none"
							viewBox="0 0 20 16">
							<path
								stroke="currentColor"
								stroke-width="2"
								d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5A5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
						</svg>
						<p class="mb-2 text-sm text-gray-500">
							<span class="font-semibold">Click to upload</span>
							or drag and drop
						</p>
						<p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (MAX. 1200x400px)</p>
					</div>
					<input
						id="dropzone-file"
						type="file"
						class="hidden"
						ref="fileInput"
						@change="handleImageUpload"
						accept="image/*" />
				</label>
			</div>

			<!-- Display Cropper if an image is uploaded -->
			<div v-if="img">
				<cropper
					:src="img"
					:stencil-props="{
						handlers: {},
						movable: false,
						resizable: false,
					}"
					:stencil-size="{ width: 1200, height: 400 }"
					image-restriction="stencil"
					ref="croppedImage" />

				<div class="flex flex-col items-start gap-4 mt-4">
					<div class="flex items-center gap-2">
						<input
							id="share-cover"
							type="checkbox"
							v-model="shareCover"
							class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
						<label
							for="share-cover"
							class="text-sm text-gray-700">
							Share this cover as a post
						</label>
					</div>
				</div>

				<div class="flex justify-between items-center w-full gap-4">
					<!-- Button to reselect image -->
					<button
						@click="reselectImage"
						class="mt-4 text-blue-500 underline">
						Reselect Image
					</button>
					<!-- Submit Button to Crop and Upload -->
					<button
						v-if="img"
						@click="cropAndSubmit"
						:disabled="isLoading"
						:class="{
							'mt-4 bg-blue-100 text-white py-2 px-4 rounded transition duration-300 ease-in-out cursor-not-allowed':
								isLoading,
							'mt-4 bg-blue-500 text-white py-2 px-4 rounded transition duration-300 ease-in-out':
								!isLoading,
						}">
						Save Cover Photo
					</button>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>

	<UpdateProfileReminder />
</template>
