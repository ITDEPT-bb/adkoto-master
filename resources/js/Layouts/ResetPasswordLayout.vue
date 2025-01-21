<script setup>
// import AdkotoLogo from "@/Components/AdkotoLogo.vue";
import { Link } from "@inertiajs/vue3";

import logo from "/public/img/1aaadkotoa1.png";
</script>

<template>
	<div
		class="h-full overflow-y-auto scrollbar-thin flex flex-col sm:mt-0 sm:justify-center mt-24 items-center sm:pt-0 bg-gray-100 dark:bg-gray-900">
		<div>
			<Link href="/">
				<!-- <logo class="w-auto h-24 fill-current text-gray-500" /> -->
				<img
					:src="logo"
					class="w-auto h-24 fill-current text-gray-500"
					alt="" />
			</Link>
		</div>

		<div
			class="w-full overflow-y-auto scrollbar-thin sm:max-w-md mt-2 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
			<slot />
		</div>
	</div>
</template>
