<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

import { ref } from "vue";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import Checkbox from "@/Components/Checkbox.vue";

import { EyeIcon, EyeSlashIcon } from "@heroicons/vue/24/solid";

const date = ref(null);

const config = ref({
	altFormat: "M j, Y",
	altInput: true,
	dateFormat: "Y-m-d",
	maxDate: "today",
});

const form = useForm({
	name: "",
	// surname: "",
	email: "",
	// contact: "",
	phone: "",
	// birthday: "",
	// gender: "",
	password: "",
	password_confirmation: "",
});

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const isValidPhilippinePhone = ref(true);

const validatePhone = () => {
	const phoneRegex = /^(?:\+63|0)9\d{9}$/;
	isValidPhilippinePhone.value = phoneRegex.test(form.phone);
};

const submit = () => {
	form.post(route("register"), {
		onFinish: () => form.reset("password", "password_confirmation"),
	});
};
</script>

<template>
	<GuestLayout>
		<Head title="Register" />

		<div className="flex flex-col gap-6">
			<div class="overflow-hidden rounded-xl bg-card text-card-foreground">
				<div className="sm:hidden flex flex-col items-center justify-center w-full h-full gap-2">
					<img
						src="img/Auth/adkoto_logo.png"
						alt="Adkoto Logo"
						className="h-auto max-w-[100%] object-contain px-6 pb-4 md:px-8" />
				</div>
				<div
					class="grid md:grid-cols-2 bg-white border shadow-lg sm:rounded-none rounded-lg sm:py-0 py-2 sm:px-0 px-1">
					<div className="relative hidden md:flex bg-blue-primary p-4">
						<div className="flex flex-col items-center justify-center w-full h-full gap-2">
							<img
								src="img/Auth/adkoto_logo.png"
								alt="Adkoto Logo"
								className="h-auto max-w-[60%] object-contain px-6 md:px-8" />

							<img
								src="img/Auth/adkoto_reg.png"
								alt="Adkoto Vector"
								className="h-auto max-w-[100%] object-contain px-6 md:px-8" />
						</div>
					</div>
					<form
						@submit.prevent="submit"
						className="p-2 md:p-6 md:py-10">
						<div className="flex flex-col gap-6">
							<div className="flex flex-col items-center text-center">
								<h1 className="text-2xl font-bold text-blue-primary">Welcome to Adkoto</h1>
								<p className="text-balance text-muted-foreground">Create your new Adkoto account</p>
							</div>

							<div class="grid gap-1 sm:gap-2">
								<InputLabel
									for="name"
									value="Name"
									class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" />

								<TextInput
									id="name"
									type="text"
									class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
									v-model="form.name"
									required
									autocomplete="name" />

								<InputError
									class="mt-2"
									:message="form.errors.name" />
							</div>

							<div class="grid gap-1 sm:gap-2">
								<InputLabel
									for="email"
									value="Email"
									class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" />

								<TextInput
									id="email"
									type="email"
									class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
									v-model="form.email"
									required
									autocomplete="username" />

								<InputError
									class="mt-2"
									:message="form.errors.email" />
							</div>

							<div class="grid gap-1 sm:gap-2">
								<InputLabel
									for="phone"
									value="Mobile No."
									class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" />

								<TextInput
									id="phone"
									v-model="form.phone"
									type="tel"
									class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
									required
									autocomplete="phone"
									@input="validatePhone" />

								<InputError
									class="mt-2"
									:message="
										!isValidPhilippinePhone
											? 'Please enter a valid Philippine phone number.'
											: form.errors.phone
									" />
							</div>

							<div class="grid gap-1 sm:gap-2 relative">
								<InputLabel
									for="password"
									value="Password"
									class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" />

								<TextInput
									:type="showPassword ? 'text' : 'password'"
									id="password"
									class="block w-full pr-10"
									v-model="form.password"
									required
									autocomplete="new-password" />

								<span
									class="absolute inset-y-0 mt-5 right-0 flex items-center pr-3 cursor-pointer text-gray-500 dark:text-gray-400"
									@click="showPassword = !showPassword">
									<EyeIcon
										v-if="!showPassword"
										class="h-6 w-6" />
									<EyeSlashIcon
										v-else
										class="h-6 w-6" />
								</span>

								<InputError
									class="mt-2"
									:message="form.errors.password" />
							</div>

							<div class="grid gap-1 sm:gap-2 relative">
								<InputLabel
									for="password_confirmation"
									value="Confirm Password"
									class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" />

								<TextInput
									:type="showPasswordConfirmation ? 'text' : 'password'"
									id="password_confirmation"
									class="block w-full pr-10"
									v-model="form.password_confirmation"
									required
									autocomplete="new-password" />

								<span
									class="absolute inset-y-0 mt-5 right-0 flex items-center pr-3 cursor-pointer text-gray-500 dark:text-gray-400"
									@click="showPasswordConfirmation = !showPasswordConfirmation">
									<EyeIcon
										v-if="!showPasswordConfirmation"
										class="h-6 w-6" />
									<EyeSlashIcon
										v-else
										class="h-6 w-6" />
								</span>

								<InputError
									class="mt-2"
									:message="form.errors.password_confirmation" />
							</div>

							<div class="grid gap-1 sm:gap-2">
								<InputLabel for="terms">
									<div class="flex items-center">
										<Checkbox
											id="terms"
											v-model:checked="form.terms"
											name="terms"
											required />

										<div class="ms-2 text-xs sm:text-normal">
											I agree to the
											<a
												target="_blank"
												:href="route('terms.show')"
												class="underline text-xs sm:text-normal text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
												>Terms of Service</a
											>
											and
											<a
												target="_blank"
												:href="route('policy.show')"
												class="underline text-xs sm:text-normal text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
												>Privacy Policy</a
											>
										</div>
									</div>
									<InputError
										class="mt-2"
										:message="form.errors.terms" />
								</InputLabel>
							</div>

							<PrimaryButton
								class="w-full bg-blue-primary hover:bg-blue-hover inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0"
								:class="{ 'opacity-25': form.processing }"
								:disabled="form.processing">
								Register
							</PrimaryButton>

							<!-- <div class="flex items-center justify-end mt-4">
								<Link
									:href="route('login')"
									class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">
									Already registered?
								</Link>

								<PrimaryButton
									class="w-full bg-blue-primary hover:bg-blue-hover inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0"
									:class="{ 'opacity-25': form.processing }"
									:disabled="form.processing">
									Register
								</PrimaryButton>
							</div> -->
						</div>
						<div className="text-center text-sm mt-4">
							Already have an account?
							<Link
								:href="route('login')"
								className="underline underline-offset-4 text-blue-primary hover:text-blue-hover">
								Sign In
							</Link>
						</div>
					</form>
				</div>
			</div>
		</div>
	</GuestLayout>
</template>
