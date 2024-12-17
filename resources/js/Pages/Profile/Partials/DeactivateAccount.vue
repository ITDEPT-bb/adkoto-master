<template>
	<section>
		<header>
			<h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Deactivate Account</h2>
			<p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
				Once you deactivate your account, you will no longer have access to your data. This action
				is irreversible.
			</p>
		</header>

		<div class="mt-6">
			<button
				@click="openModal"
				class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
				Deactivate Account
			</button>
		</div>

		<!-- Deactivate Account Modal -->
		<transition name="fade">
			<div
				v-if="isModalOpen"
				class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 flex items-center justify-center">
				<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">
					<h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirm Deactivation</h3>
					<p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
						To deactivate your account, please confirm your password.
					</p>

					<div
						v-if="step === 1"
						class="mt-4">
						<!-- Step 1: Password Confirmation -->
						<label
							for="password"
							class="block text-sm text-gray-700 dark:text-gray-300">
							Password
						</label>
						<input
							type="password"
							id="password"
							v-model="password"
							class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
							placeholder="Enter your password" />
						<div
							v-if="error"
							class="mt-2 text-sm text-red-600">
							{{ error }}
						</div>
						<div class="mt-6 flex justify-end">
							<button
								@click="cancelModal"
								class="mr-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded focus:outline-none"
								:disabled="isLoading">
								<span>Cancel</span>
							</button>
							<!-- <button
								@click="validatePassword"
								:disabled="isLoading"
								class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
								<span v-if="!isLoading">Next</span>
								<span v-else>Loading...</span>
							</button> -->
							<button
								type="button"
								@click="validatePassword"
								:class="[
									'rounded px-4 py-2 text-sm font-semibold text-white shadow-sm',
									isLoading ? 'bg-gray-300 cursor-not-allowed' : 'bg-red-600 hover:bg-red-500',
									isLoading
										? ''
										: 'focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600',
								]"
								:disabled="isLoading">
								<span v-if="!isLoading">Next</span>
								<span v-else>Loading...</span>
							</button>
						</div>
					</div>

					<div
						v-else
						class="mt-4">
						<!-- Step 2: Confirm Deactivation -->
						<p class="text-sm text-gray-600 dark:text-gray-400">
							Are you sure you want to deactivate your account?
						</p>
						<div class="mt-6 flex justify-end">
							<button
								@click="cancelModal"
								class="mr-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded focus:outline-none"
								:disabled="isLoading">
								<span>Cancel</span>
							</button>
							<!-- <button
								@click="deactivateAccount"
								:disabled="isLoading"
								class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
								<span v-if="!isLoading">Deactivate</span>
								<span v-else>Loading...</span>
							</button> -->
							<button
								type="button"
								@click="deactivateAccount"
								:class="[
									'rounded px-4 py-2 text-sm font-semibold text-white shadow-sm',
									isLoading ? 'bg-gray-300 cursor-not-allowed' : 'bg-red-600 hover:bg-red-500',
									isLoading
										? ''
										: 'focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600',
								]"
								:disabled="isLoading">
								<span v-if="!isLoading">Deactivate</span>
								<span v-else>Loading...</span>
							</button>
						</div>
					</div>
				</div>
			</div>
		</transition>
	</section>
</template>

<script setup>
import { ref } from "vue";

const isModalOpen = ref(false);
const step = ref(1);
const password = ref("");
const error = ref(null);
const isLoading = ref(false);

// Open the modal
const openModal = () => {
	isModalOpen.value = true;
	step.value = 1;
	password.value = "";
	error.value = null;
};

// Cancel the modal
const cancelModal = () => {
	isModalOpen.value = false;
};

// Validate the password
const validatePassword = async () => {
	if (!password.value) {
		error.value = "Password is required.";
		return;
	}

	isLoading.value = true;

	try {
		const response = await axios.post(route("account.validatePassword"), {
			password: password.value,
		});

		if (response.data.success) {
			step.value = 2;
			error.value = null;
		} else {
			error.value = response.data.error || "The password is incorrect.";
		}
	} catch (err) {
		error.value = "An error occurred. Please try again.";
		console.error(err);
	} finally {
		isLoading.value = false;
	}
};

// Deactivate the account
const deactivateAccount = async () => {
	isLoading.value = true;

	try {
		const response = await axios.delete(route("account.deactivate"));

		if (response.data.success) {
			window.location.href = route("dashboard");
		} else {
			error.value = "An error occurred while deactivating your account. Please try again.";
		}
	} catch (err) {
		error.value = "An error occurred while deactivating your account. Please try again.";
		console.error(err);
	} finally {
		isLoading.value = false;
	}
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
	transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
	opacity: 0;
}
</style>
