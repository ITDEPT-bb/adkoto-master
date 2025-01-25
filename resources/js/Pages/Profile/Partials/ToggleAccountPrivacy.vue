<template>
	<section>
		<header>
			<h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Privacy Settings</h2>
			<p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
				Manage your account's privacy settings.
			</p>
		</header>

		<form
			@submit.prevent="form.patch(route('profile.updatePrivacy'))"
			class="mt-6 space-y-6">
			<div>
				<InputLabel
					for="is_private"
					value="Account Privacy" />
				<div class="mt-2">
					<select
						id="is_private"
						v-model="form.is_private"
						class="block w-full mt-1 rounded-md border-gray-300 dark:bg-gray-800 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
						<option value="0">Public</option>
						<option value="1">Private</option>
					</select>
				</div>
			</div>

			<div class="flex items-center gap-4">
				<PrimaryButton :disabled="form.processing">Save</PrimaryButton>
				<Transition
					enter-active-class="transition ease-in-out"
					enter-from-class="opacity-0"
					leave-active-class="transition ease-in-out"
					leave-to-class="opacity-0">
					<p
						v-if="form.recentlySuccessful"
						class="text-sm text-gray-600 dark:text-gray-400">
						Saved.
					</p>
				</Transition>
			</div>
		</form>
	</section>
</template>

<script setup>
import { defineProps, watch } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const user = usePage().props.auth.user;

const props = defineProps({
	isPrivate: {
		type: Boolean,
		required: true,
	},
});

const form = useForm({
	// is_private: props.isPrivate ? 1 : 0,
	is_private: user.is_private ? 1 : 0,
});

watch(
	// () => props.isPrivate,
	() => user.is_private,
	(newVal) => {
		form.is_private = newVal ? 1 : 0;
	}
);
</script>
