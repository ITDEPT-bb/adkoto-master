<template>
	<Head title="Notifications" />

	<AuthenticatedLayout>
		<div class="bg-gray-100 py-4 px-8">
			<h1 class="text-2xl font-bold text-gray-800">All Notifications</h1>
		</div>
		<div class="container mx-auto p-4 py-2 h-full overflow-y-auto">
			<template v-if="notifications.length === 0">
				<div class="p-4 text-center text-gray-500">No notifications available.</div>
			</template>
			<ul
				v-else
				class="max-w-full divide-y divide-gray-200 bg-white rounded-lg shadow-lg">
				<template
					v-for="notification in notifications"
					:key="notification.id">
					<a
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
							</div>
							<div class="flex-1 min-w-0">
								<p
									:class="{
										'text-base': true,
										'font-semibold': notification.read_at === null,
										'font-normal': notification.read_at !== null,
										'text-gray-900': true,
									}">
									{{ notification.data.message }}
								</p>
								<p class="text-sm text-gray-500">
									{{ dayjs(notification.created_at).fromNow() }}
								</p>
							</div>
						</li>
					</a>
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
import { Head } from "@inertiajs/vue3";
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
});

const notifications = ref(props.notifications);

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
