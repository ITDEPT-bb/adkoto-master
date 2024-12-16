<script setup>
import AdkotoLogo from "@/Components/AdkotoLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
	<div
		class="h-full overflow-y-auto flex flex-col sm:mt-0 sm:justify-center mt-24 items-center sm:pt-0 bg-gray-100 dark:bg-gray-900">
		<div>
			<Link href="/">
				<AdkotoLogo class="w-auto h-24 fill-current text-gray-500" />
			</Link>
		</div>

		<div
			class="w-full overflow-y-auto sm:max-w-md mt-2 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
			<slot />
		</div>
	</div>
</template>
