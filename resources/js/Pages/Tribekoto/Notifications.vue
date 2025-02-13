<template>
	<Head title="Notifications" />

	<AuthenticatedLayout>
		<div class="pt-10 px-8">
			<h1 class="text-2xl font-bold text-gray-800">All Notifications</h1>
		</div>
		<div
			v-show="showNotification && success"
			class="my-2 py-2 px-3 font-medium text-sm bg-emerald-500 text-white">
			{{ success }}
		</div>
		<div class="container mx-auto p-4 py-2 h-full overflow-y-auto scrollbar-thin">
			<div class="flex justify-between mb-4">
				<!-- Mark All as Read Button -->
				<button
					@click="handleMarkAllAsRead"
					:disabled="isLoading"
					title="Mark All as Read"
					class="flex items-center gap-2 px-4 py-2 text-white font-medium rounded-lg transition-all duration-300"
					:class="{
						'bg-gray-300 cursor-not-allowed': isLoading,
						'bg-blue-500 hover:bg-blue-600': !isLoading,
					}">
					<template v-if="!isLoading">
						<span>Mark All as Read</span>
					</template>
					<p v-else>Loading...</p>
				</button>

				<!-- Delete All Notifications Button -->
				<button
					@click="handleDeleteAllNotifications"
					:disabled="isLoading"
					title="Delete All Notifications"
					class="flex items-center gap-2 px-4 py-2 text-white font-medium rounded-lg transition-all duration-300"
					:class="{
						'bg-gray-300 cursor-not-allowed': isLoading,
						'bg-red-500 hover:bg-red-600': !isLoading,
					}">
					<template v-if="!isLoading">
						<span>Delete All</span>
					</template>
					<p v-else>Loading...</p>
				</button>
			</div>

			<template v-if="notifications.length === 0">
				<div class="p-4 text-center text-gray-500">No notifications available.</div>
			</template>
			<ul
				v-else
				class="max-w-full divide-y divide-gray-200 bg-white rounded-lg shadow-lg">
				<template
					v-for="notification in notifications"
					:key="notification.id">
					<Link
						:href="notification.data.route"
						@click="markAsRead(notification)">
						<li
							class="py-4 px-6 cursor-pointer flex items-start space-x-4 border-b last:border-b-0 hover:bg-blue-200 transition-colors duration-300 ease-in-out">
							<div class="flex-shrink-0">
								<img
									class="w-12 h-12 rounded-full border border-gray-300"
									v-if="notification.data.user_id && userProfiles[notification.data.user_id]"
									:src="userProfiles[notification.data.user_id]?.avatar_url"
									alt="User Avatar" />
								<span
									v-else
									class="text-sm text-gray-500">
									Loading...
								</span>
								<!-- <div class="absolute">
									<template v-if="notification.data.reaction_image">
										<img
											:src="notification.data.reaction_image"
											:alt="notification.data.reaction"
											class="w-6 h-6 object-contain relative bottom-5 left-7" />
									</template>
								</div> -->
							</div>
							<div class="flex-1 min-w-0">
								<div class="flex flex-row gap-2">
									<p
										:class="{
											'text-base': true,
											'font-semibold': notification.read_at === null,
											'font-normal': notification.read_at !== null,
											'text-gray-900': true,
										}">
										{{ notification.data.message }}
									</p>
									<template v-if="notification.data.reaction_image">
										<img
											:src="notification.data.reaction_image"
											:alt="notification.data.reaction"
											class="w-6 h-6 object-contain" />
									</template>

									<template v-else-if="notification.data.icon">
										<span class="w-6 h-6 flex items-center justify-center text-md">
											{{ notification.data.icon }}
										</span>
									</template>
								</div>
								<p class="text-sm text-gray-500">
									{{ dayjs(notification.created_at).fromNow() }}
								</p>
							</div>
						</li>
					</Link>
				</template>
			</ul>
		</div>
	</AuthenticatedLayout>
	<UpdateProfileReminder />
</template>

<script setup>
import { defineProps, ref } from "vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/NotificationLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import axiosClient from "@/axiosClient.js";

dayjs.extend(relativeTime);

const props = defineProps({
	notifications: {
		type: Array,
		required: true,
	},
	userProfiles: {
		type: Object,
		required: true,
	},
	status: {
		type: String,
	},
	success: {
		type: String,
	},
});

const showNotification = ref(true);
const notifications = ref(props.notifications);
const isLoading = ref(false);

function handleMarkAllAsRead() {
	isLoading.value = true;

	const form = useForm({});
	form.post(route("notifications.markAllAsRead"), {
		preserveScroll: true,
		onFinish: () => {
			isLoading.value = false;
		},
	});
}

function handleDeleteAllNotifications() {
	isLoading.value = true;

	const form = useForm({});
	form.post(route("notifications.deleteAllNotif"), {
		preserveScroll: true,
		onFinish: () => {
			isLoading.value = false;
		},
	});
}

const markAsRead = async (notification) => {
	if (!notification.read_at) {
		try {
			await axiosClient.post(`/notifications/${notification.id}/mark-as-read`);
			notification.read_at = new Date().toISOString();
			window.location.href = notification.data.route;
		} catch (error) {
			console.error("Failed to mark notification as read", error);
		}
	} else {
		window.location.href = notification.data.route;
	}
};
</script>
