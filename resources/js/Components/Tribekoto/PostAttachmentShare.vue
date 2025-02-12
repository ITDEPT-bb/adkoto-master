<script setup>
import { ArrowDownTrayIcon } from "@heroicons/vue/24/outline";
import { Link } from "@inertiajs/vue3";

defineProps({
	attachments: Array,
	viewUrl: String,
});

defineEmits(["attachmentClick"]);
</script>

<template>
	<template v-for="(attachment, ind) of attachments.slice(0, 4)">
		<Link
			class="group bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative cursor-pointer"
			:href="viewUrl">
			<div>
				<div
					v-if="ind === 3 && attachments.length > 4"
					class="absolute left-0 top-0 right-0 bottom-0 z-10 bg-black/60 text-white flex items-center justify-center text-2xl">
					+{{ attachments.length - 4 }} more
				</div>

				<!-- Download-->
				<a
					@click.stop
					:href="route('post.download', attachment)"
					class="z-20 opacity-0 group-hover:opacity-100 transition-all w-8 h-8 flex items-center justify-center text-gray-100 bg-gray-700 rounded absolute right-2 top-2 cursor-pointer hover:bg-gray-800">
					<ArrowDownTrayIcon class="w-4 h-4" />
				</a>
				<!--/ Download-->

				<img
					:src="attachment.url"
					class="object-contain" />
			</div>
		</Link>
	</template>
</template>
