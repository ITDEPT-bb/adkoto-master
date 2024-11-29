<script setup>
import { computed, ref } from "vue";
import { XMarkIcon, BookmarkIcon } from "@heroicons/vue/24/solid";
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from "@headlessui/vue";
import { useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputTextarea from "@/Components/InputTextarea.vue";
import axiosClient from "@/axiosClient.js";
import GroupForm from "@/Components/GroupChat/GroupChatForm.vue";
import BaseModal from "@/Components/Tribekoto/BaseModal.vue";
import CallUser from "@/Components/Chat/CallUser.vue";

const props = defineProps({
	modelValue: Boolean,
	user: Object,
	agora_id: String,
});

const authUser = usePage().props.auth.user;

const formErrors = ref({});

const showModal = computed({
	get: () => props.modelValue,
	set: (value) => emit("update:modelValue", value),
});

const emit = defineEmits(["update:modelValue"]);

const show = computed({
	get: () => props.modelValue,
	set: (value) => emit("update:modelValue", value),
});

function closeModal() {
	show.value = false;
}
</script>

<template>
	<BaseModal
		title="Create new Group Chat"
		v-model="show"
		@hide="closeModal">
		<div class="p-4 dark:text-gray-100">
			<CallUser
				:user="user"
				:agora_id="agora_id"
				:authuser="authUser.name"
				:authuserid="authUser.id" />
		</div>

		<div class="flex justify-end gap-2 py-3 px-4">
			<button
				@click="show = false"
				class="text-gray-800 flex gap-1 items-center justify-center bg-gray-100 rounded-md hover:bg-gray-200 py-2 px-4">
				<XMarkIcon class="w-5 h-5" />
				Cancel
			</button>
			<button
				type="button"
				@click="submit"
				:class="[
					isLoading ? 'bg-gray-300 cursor-not-allowed' : 'bg-red-600 hover:bg-red-500',
					'flex items-center justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600',
				]"
				:disabled="isLoading">
				<BookmarkIcon class="w-4 h-4 mr-2" />
				<span v-if="!isLoading">Submit</span>
				<span v-else>Loading...</span>
			</button>
		</div>
	</BaseModal>
</template>
