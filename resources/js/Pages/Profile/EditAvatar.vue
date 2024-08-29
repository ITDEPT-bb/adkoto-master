<template>
    <Head title="Update Your Profile Picture" />
    <AuthenticatedLayout>
        <div class="max-w-md mx-auto p-4">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">
                Update Your Avatar
            </h1>

            <div
                v-if="!img"
                class="flex items-center justify-center w-full mb-4"
            >
                <label
                    for="avatar-file"
                    class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100"
                >
                    <div
                        class="flex flex-col items-center justify-center pt-5 pb-6"
                    >
                        <svg
                            class="w-8 h-8 mb-4 text-gray-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 20 16"
                        >
                            <path
                                stroke="currentColor"
                                stroke-width="2"
                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5A5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"
                            />
                        </svg>
                        <p class="mb-2 text-sm text-gray-500">
                            <span class="font-semibold">Click to upload</span>
                            or drag and drop
                        </p>
                        <p class="text-xs text-gray-500">
                            SVG, PNG, JPG or GIF (MAX. 800x800px)
                        </p>
                    </div>
                    <input
                        id="avatar-file"
                        type="file"
                        class="hidden"
                        ref="fileInput"
                        @change="handleImageUpload"
                        accept="image/*"
                    />
                </label>
            </div>

            <div v-if="img">
                <cropper
                    :src="img"
                    :stencil-props="{
                        handlers: {},
                        movable: false,
                        resizable: false,
                    }"
                    :stencil-size="{ width: 300, height: 300 }"
                    :stencil-shape="'circle'"
                    class="rounded-full"
                    image-restriction="stencil"
                    ref="croppedImage"
                />
                <div class="flex gap-4">
                    <button
                        @click="reselectImage"
                        class="mt-4 text-blue-500 underline"
                    >
                        Reselect Image
                    </button>
                    <button
                        @click="cropAndSubmit"
                        :disabled="isLoading"
                        :class="{
                            'mt-4 bg-blue-100 text-white py-2 px-4 rounded transition duration-300 ease-in-out cursor-not-allowed':
                                isLoading,
                            'mt-4 bg-blue-500 text-white py-2 px-4 rounded transition duration-300 ease-in-out':
                                !isLoading,
                        }"
                    >
                        Save Avatar
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
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

// Function to crop and submit the image
const cropAndSubmit = async () => {
    isLoading.value = true;
    const canvas = croppedImage.value.getCanvas();
    if (canvas) {
        const croppedDataURL = canvas.toDataURL();

        const resizedDataURL = await resizeImage(croppedDataURL, 300, 300);
        const blob = await (await fetch(resizedDataURL)).blob();
        const formData = new FormData();
        formData.append("avatar", blob, "avatar.jpg");

        try {
            const response = await axiosClient.post(
                "/profile/update-avatar",
                formData,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );

            if (response.status === 200) {
                toast.success("Avatar has been updated successfully!");
                window.location.href = "/";
            }
        } catch (error) {
            console.error("Error uploading image:", error);
            toast.error("There was an error updating your avatar.");
        } finally {
            isLoading.value = false;
        }
    }
};

// Function to reselect image
const reselectImage = () => {
    img.value = null;
    fileInput.value.value = null;
};
</script>

<style scoped>
.cropper {
    border-radius: 50% !important;
}
</style>
