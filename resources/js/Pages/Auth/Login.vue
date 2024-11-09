<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

import { EyeIcon, EyeSlashIcon } from "@heroicons/vue/24/solid";

defineProps({
	canResetPassword: {
		type: Boolean,
	},
	status: {
		type: String,
	},
});

const form = useForm({
	// email: '',
	login: "",
	password: "",
	remember: false,
});

const showPassword = ref(false);

const submit = () => {
	form.post(route("login"), {
		onFinish: () => form.reset("password"),
	});
};
</script>

<template>
	<GuestLayout>
		<Head title="Log in" />
		<div class="text-center dark:text-gray-100">
			<h2 class="text-2xl text-center">Login</h2>
			<span class="text-gray-400 text-sm">or</span>
			<div class="flex justify-center">
				<Link
					:href="route('register')"
					class="inline-block hover:underline">
					Create new account
				</Link>
			</div>
		</div>

		<div
			v-if="status"
			class="mb-4 font-medium text-sm text-green-600">
			{{ status }}
		</div>

		<form @submit.prevent="submit">
			<!-- <div>
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div> -->
			<div class="mt-4">
				<InputLabel
					for="login"
					value="Email or Phone Number" />

				<TextInput
					id="login"
					type="text"
					class="mt-1 block w-full"
					v-model="form.login"
					required
					autofocus
					autocomplete="username" />

				<InputError
					class="mt-2"
					:message="form.errors.login" />
			</div>

			<!-- <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div> -->
			<div class="mt-4 relative">
				<InputLabel
					for="password"
					value="Password" />

				<TextInput
					:type="showPassword ? 'text' : 'password'"
					id="password"
					class="mt-1 block w-full pr-10"
					v-model="form.password"
					required
					autocomplete="current-password" />

				<span
					class="absolute inset-y-0 mt-5 right-0 flex items-center pr-3 cursor-pointer text-gray-500 dark:text-gray-400"
					@click="showPassword = !showPassword">
					<EyeIcon
						class="h-6 w-6"
						v-if="!showPassword" />
					<EyeSlashIcon
						class="h-6 w-6"
						v-else />
				</span>

				<InputError
					class="mt-2"
					:message="form.errors.password" />
			</div>

			<div class="block mt-4">
				<label class="flex items-center">
					<Checkbox
						name="remember"
						v-model:checked="form.remember" />
					<span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
				</label>
			</div>

			<div class="flex items-center justify-end mt-4">
				<Link
					v-if="canResetPassword"
					:href="route('password.request')"
					class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
					Forgot your password?
				</Link>

				<PrimaryButton
					class="ms-4"
					:class="{ 'opacity-25': form.processing }"
					:disabled="form.processing">
					Log in
				</PrimaryButton>
			</div>
		</form>
	</GuestLayout>
</template>
