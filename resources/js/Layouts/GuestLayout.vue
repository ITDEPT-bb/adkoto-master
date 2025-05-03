<script setup>
import AdkotoLogo from "@/Components/AdkotoLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <!-- <div
		class="h-full overflow-y-auto flex flex-col sm:mt-0 sm:justify-center mt-24 items-center sm:pt-0 bg-gray-100 dark:bg-gray-900">
		<div>
			<Link href="/">
				<AdkotoLogo class="w-auto h-32 fill-current text-gray-500 pt-1" />
			</Link>
		</div>

		<div
			class="w-full overflow-y-auto sm:max-w-md px-6 py-4 bg-white dark:bg-gray-800 border border-gray-300 shadow-xl shadow-gray-400 sm:rounded-lg">
			<slot />
		</div>
	</div> -->

    <div
        className="flex min-h-svh flex-col items-center justify-center gap-6 bg-gray-300 dark:bg-gray-800 p-6 md:p-10"
    >
        <div className="w-full max-w-sm md:max-w-3xl">
            <slot />
        </div>
    </div>
</template>
