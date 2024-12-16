<script setup>
import { ref, onMounted } from "vue";

const showOverlay = ref(false);
const text = ref("");
const fullText = "Where Real Human Connects";

onMounted(() => {
	if (!sessionStorage.getItem("welcomeOverlayShown")) {
		sessionStorage.setItem("welcomeOverlayShown", "true");
		showOverlay.value = true;

		let index = 0;

		const typeInterval = setInterval(() => {
			if (index < fullText.length) {
				text.value += fullText[index];
				index++;
			} else {
				clearInterval(typeInterval);
			}
		}, 100);

		setTimeout(() => {
			showOverlay.value = false;
		}, 3000);
	}
});
</script>

<template>
	<div
		v-if="showOverlay"
		class="fixed inset-0 bg-gradient-to-br from-white via-gray-100 to-gray-200 flex items-center justify-center z-50">
		<div class="text-center">
			<h1 class="text-gray-800 text-4xl lg:text-6xl font-bold tracking-wider animate-fade-in">
				{{ text }}
			</h1>
			<div class="w-16 h-1 mt-3 bg-gray-800 rounded animate-expand-line"></div>
		</div>
	</div>
</template>

<style scoped>
/* Fade-in animation for text */
@keyframes fadeIn {
	from {
		opacity: 0;
	}
	to {
		opacity: 1;
	}
}
.animate-fade-in {
	animation: fadeIn 2s ease-in;
}

/* Smooth line expansion under the text */
@keyframes expandLine {
	from {
		width: 0;
	}
	to {
		width: 4rem; /* Matches the `w-16` in the template */
	}
}
.animate-expand-line {
	animation: expandLine 1s ease-in-out;
}
</style>
