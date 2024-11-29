<template>
	<Head title="Conversation" />
	<AuthenticatedLayout>
		<div
			class="flex-1 my-2 pb-6 justify-between max-w-7xl mx-auto bg-white rounded-lg border flex flex-col h-full">
			<CallUser
				:user="user"
				agora_id="{{ env('AGORA_APP_ID') }}"
				:authuser="authUser.name"
				:authuserid="authUser.id" />
		</div>
	</AuthenticatedLayout>
	<UpdateProfileReminder />
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/ChatLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import CallUser from "@/Components/Chat/CallUser.vue";
import { usePage, Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axiosClient from "@/axiosClient.js";

const props = defineProps({
	user: Object,
	agora_id: String,
});

const user = props.user;
const authUser = usePage().props.auth.user;
</script>
