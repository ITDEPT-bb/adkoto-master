<script setup>
import { ref, onMounted } from "vue";

const showOverlay = ref(false);
const text = ref("");
const fullText = "Where Real Humans Connect";
const isTypingComplete = ref(false);

onMounted(() => {
	if (!sessionStorage.getItem("welcomeOverlayShown")) {
		sessionStorage.setItem("welcomeOverlayShown", "true");
		showOverlay.value = true;

		let index = 0;

		const typeInterval = setInterval(() => {
			text.value += fullText[index];
			index++;

			if (index >= fullText.length) {
				clearInterval(typeInterval);
				isTypingComplete.value = true;

				setTimeout(() => {
					showOverlay.value = false;
				}, 2000);
			}
		}, 75);
	}
});
</script>

<template>
	<div
		v-if="showOverlay"
		class="fixed inset-0 bg-gradient-to-br from-white via-gray-100 to-gray-200 flex items-center justify-center z-50">
		<div class="text-center relative">
			<h1 class="text-gray-800 text-4xl lg:text-6xl font-bold tracking-wider animate-fade-in">
				{{ text }}
			</h1>

			<!-- Loading Animation (only visible after typing) -->
			<div
				v-if="isTypingComplete"
				class="absolute inset-x-0 -bottom-10 flex justify-center items-center">
				<div class="loader"></div>
			</div>

			<!-- Decorative Line -->
			<div class="w-16 h-1 mt-3 bg-gray-800 rounded animate-expand-line"></div>
		</div>
	</div>
</template>

<style scoped>
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

@keyframes expandLine {
	from {
		width: 0;
	}
	to {
		width: 4rem;
	}
}
.animate-expand-line {
	animation: expandLine 1s ease-in-out;
}

.loader {
	width: 2rem;
	height: 2rem;
	border: 4px solid #ddd;
	border-top: 4px solid #888;
	border-radius: 50%;
	animation: spin 1s linear infinite;
}

/* Spinning animation */
@keyframes spin {
	from {
		transform: rotate(0deg);
	}
	to {
		transform: rotate(360deg);
	}
}
</style>
