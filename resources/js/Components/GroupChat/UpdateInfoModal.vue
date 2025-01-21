<template>
	<TransitionRoot
		as="template"
		:show="isOpen">
		<Dialog
			class="relative z-10"
			@close="closeModal">
			<TransitionChild
				as="template"
				enter="ease-out duration-300"
				enter-from="opacity-0"
				enter-to="opacity-100"
				leave="ease-in duration-200"
				leave-from="opacity-100"
				leave-to="opacity-0">
				<div class="fixed inset-0 bg-gray-600 bg-opacity-90 transition-opacity" />
			</TransitionChild>

			<div class="fixed inset-0 z-10 w-screen overflow-y-auto scrollbar-thin">
				<div
					class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
					<TransitionChild
						as="template"
						enter="ease-out duration-300"
						enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
						enter-to="opacity-100 translate-y-0 sm:scale-100"
						leave="ease-in duration-200"
						leave-from="opacity-100 translate-y-0 sm:scale-100"
						leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
						<DialogPanel
							class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl">
							<div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
								<div class="sm:items-start">
									<div
										class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
										<ProfileIcon
											class="h-6 w-6 text-blue-600"
											aria-hidden="true" />
									</div>
									<div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
										<DialogTitle
											as="h3"
											class="text-base mt-3 font-semibold leading-6 text-gray-900">
											Update the Group Chat Infomation
										</DialogTitle>
										<div class="mt-2 p-4 dark:text-gray-100">
											<div class="space-y-6">
												<!-- Group Chat Name -->
												<div class="mb-3 dark:text-gray-100">
													<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
														Group Chat Name
													</label>
													<TextInput
														type="text"
														class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
														v-model="form.name"
														required />
												</div>

												<!-- Group Chat Description -->
												<div class="mb-3 dark:text-gray-100">
													<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
														Group Chat Description
													</label>
													<InputTextarea
														v-model="form.description"
														class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></InputTextarea>
													<!-- <div
                                                        class="mt-1 text-gray-600 text-sm"
                                                    >
                                                        {{
                                                            remainingCharacters
                                                        }}
                                                        characters remaining
                                                    </div> -->
												</div>

												<!-- Group Chat Image -->
												<div class="mb-3 dark:text-gray-100">
													<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
														Group Chat Image
													</label>
													<input
														type="file"
														accept="image/*"
														@change="handleImageChange"
														form.value.photo="file;"
														class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 focus:outline-none" />
												</div>

												<!-- Image Preview -->
												<div
													v-if="imagePreview"
													class="mb-3">
													<img
														:src="imagePreview"
														alt="Image Preview"
														class="w-32 h-32 rounded-full object-cover" />
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="px-4 py-3 sm:flex sm:flex-row-reverse mx-2 gap-4 sm:px-6">
								<button
									@click="closeModal"
									class="text-gray-800 flex gap-1 items-center justify-center bg-gray-100 rounded-md hover:bg-gray-200 py-2 px-4">
									Cancel
								</button>
								<button
									type="button"
									@click="submit"
									:class="[
										isLoading ? 'bg-gray-300 cursor-not-allowed' : 'bg-red-600 hover:bg-red-500',
										'flex items-center justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm',
									]"
									:disabled="isLoading">
									<span v-if="!isLoading">Save Changes</span>
									<span v-else>Loading...</span>
								</button>
							</div>
						</DialogPanel>
					</TransitionChild>
				</div>
			</div>
		</Dialog>
	</TransitionRoot>
</template>

<script setup>
import { ref, defineProps, defineEmits, computed, onMounted } from "vue";
import TextInput from "@/Components/TextInput.vue";
import InputTextarea from "@/Components/InputTextarea.vue";
import { Link } from "@inertiajs/vue3";
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from "@headlessui/vue";
import axiosClient from "@/axiosClient.js";

import ProfileIcon from "../Icons/ProfileIcon.vue";
import GroupUpdateForm from "./GroupUpdateForm.vue";

const props = defineProps({
	isOpen: Boolean,
	groupChat: Object,
});

const emit = defineEmits(["close", "update:modelValue", "update", "hide"]);

const form = ref({
	name: props.groupChat.name || "",
	description: props.groupChat.description || "",
	photo: null,
	image_url: props.groupChat.photo || null,
});

const imagePreview = ref(form.image_url || null);

function handleImageChange(event) {
	const file = event.target.files[0];
	if (file) {
		form.value.photo = file;

		const reader = new FileReader();
		reader.onload = (e) => {
			imagePreview.value = e.target.result;
		};
		reader.readAsDataURL(file);
	}
}

const isLoading = ref(false);
const show = computed({
	get: () => props.modelValue,
	set: (value) => emit("update:modelValue", value),
});

function closeModal() {
	show.value = false;
	emit("close");
}

function submit() {
	if (isLoading.value) return;
	isLoading.value = true;

	const formData = new FormData();
	formData.append("name", form.value.name);
	formData.append("description", form.value.description);

	if (form.value.photo) {
		formData.append("photo", form.value.photo);
	}

	axiosClient
		.post(route("group-chats.update", props.groupChat.id), formData, {
			headers: { "Content-Type": "multipart/form-data" },
		})
		.then(({ data }) => {
			closeModal();
			window.location.reload();
		})
		.catch((error) => {
			console.error(error);
		})
		.finally(() => {
			isLoading.value = false;
		});
}

// const maxCharacters = 20;
// const remainingCharacters = ref(maxCharacters);

// const updateCharacterCount = () => {
//     remainingCharacters.value = maxCharacters - form.value.description.length;
// };
</script>

<style scoped></style>
