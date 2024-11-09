<template>
	<Head title="Chat" />

	<AuthenticatedLayout>
		<div class="home-page flex h-full overflow-y-auto container p-4 mx-auto">
			<FollowingList
				:followings="followings"
				:groupChats="groupChats" />
		</div>
	</AuthenticatedLayout>
	<UpdateProfileReminder />
</template>

<script setup>
import { defineProps } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/ChatLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import FollowingList from "@/Components/Chat/FollowingList.vue";

const props = defineProps({
	followings: {
		type: Array,
		required: true,
	},
	// participants: {
	//     type: Array,
	//     required: true,
	// },
	groupChats: {
		type: Array,
		required: true,
	},
});
</script>
