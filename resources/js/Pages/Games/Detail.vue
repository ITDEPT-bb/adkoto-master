<template>
	<Head title="Play Game" />
	<AuthenticatedLayout>
		<div class="container mx-auto px-6 h-full scrollbar-thin">
			<div
				v-if="game"
				class="flex flex-col items-center my-2">
				<iframe
					:src="game.iframe_url"
					width="100%"
					height="500"
					class="border border-gray-300 bg-black rounded-lg"
					allowfullscreen
					sandbox="allow-same-origin allow-scripts"
					@error="handleError"></iframe>
				<p
					v-if="iframeError"
					class="text-red-500 mt-4">
					Failed to load game.
				</p>
			</div>
			<div v-else>
				<p>Loading...</p>
			</div>
		</div>
	</AuthenticatedLayout>
	<UpdateProfileReminder />
</template>

<script setup>
import { ref, defineProps } from "vue";
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

const props = defineProps({
	game: Object,
});

const iframeError = ref(false);

function handleError() {
	iframeError.value = true;
}
</script>
