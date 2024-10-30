<template>
	<div class="youtube-live-stream mx-4 my-6">
		<iframe
			class="w-full h-[500px] rounded-lg"
			:src="liveStreamUrl"
			frameborder="0"
			allowfullscreen>
		</iframe>
	</div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
	channelId: {
		type: String,
		required: true,
	},
});

const liveStreamUrl = ref(`https://www.youtube.com/embed/live_stream?channel=${props.channelId}`);
</script>

<style scoped></style>
