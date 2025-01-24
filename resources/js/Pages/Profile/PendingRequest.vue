<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import UserListItem from "@/Components/Tribekoto/UserListItem.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

import { Head } from "@inertiajs/vue3";

const props = defineProps({
	user: {
		type: Object,
	},
	PendingFollowers: Array,
});

const isLoading = ref(false);

function acceptFollowRequest(userId) {
	isLoading.value = true;

	const form = useForm({
		follow: false,
	});

	form.post(route("user.accept", userId), {
		preserveScroll: true,
		onFinish: () => {
			isLoading.value = false;
		},
	});
}

function rejectFollowRequest(userId) {
	isLoading.value = true;

	const form = useForm({
		follow: false,
	});

	form.post(route("user.reject", userId), {
		preserveScroll: true,
		onFinish: () => {
			isLoading.value = false;
		},
	});
}
</script>

<template>
	<Head title="Profile" />

	<AuthenticatedLayout>
		<div class="py-6 h-full overflow-y-auto scrollbar-thin mx-auto px-10">
			<div class="flex-1 px-4">
				<!-- Pending Follow Requests Section -->
				<div
					v-if="PendingFollowers.length"
					class="mb-6">
					<h3 class="text-lg font-semibold text-gray-800 mb-4">Pending Follow Requests</h3>
					<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
						<UserListItem
							v-for="user of PendingFollowers"
							:key="user.id"
							:user="user"
							class="shadow-lg rounded-lg p-4 bg-white">
							<template v-slot:actions>
								<div class="flex gap-4">
									<!-- Accept Follow Request Button -->
									<PrimaryButton
										@click="acceptFollowRequest(user.id)"
										:disabled="isLoading || user.isAccepted"
										:class="{
											'bg-gray-300 text-gray-500 cursor-not-allowed': isLoading || user.isAccepted,
											'bg-green-500 hover:bg-green-600 text-white': !isLoading && !user.isAccepted,
										}">
										<span v-if="!isLoading && !user.isAccepted">Accept</span>
										<span
											v-else
											class="hidden sm:inline"
											>Loading...</span
										>
									</PrimaryButton>

									<!-- Reject Follow Request Button -->
									<PrimaryButton
										@click="rejectFollowRequest(user.id)"
										:disabled="isLoading || user.isAccepted"
										:class="{
											'bg-gray-300 text-gray-500 cursor-not-allowed': isLoading || user.isAccepted,
											'bg-red-500 hover:bg-red-600 text-white': !isLoading && !user.isAccepted,
										}">
										<span v-if="!isLoading && !user.isAccepted">Reject</span>
										<span
											v-else
											class="hidden sm:inline"
											>Loading...</span
										>
									</PrimaryButton>
								</div>
							</template>
						</UserListItem>
					</div>
				</div>

				<!-- No Pending Requests Message -->
				<div
					v-else
					class="flex flex-col items-center justify-center h-64">
					<p class="text-gray-500 text-lg">No pending follow requests.</p>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
