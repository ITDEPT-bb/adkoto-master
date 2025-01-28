<template>
	<div class="border-b py-4">
		<div class="flex flex-col sm:flex-row items-start space-y-4 sm:space-y-0 sm:space-x-4">
			<!-- Image Section -->
			<Link
				:href="
					route('adkoto.show', {
						id: advertisement.id,
					})
				">
				<img
					:src="advertisement.attachments[0]?.image_path || 'https://via.placeholder.com/100'"
					alt="Listing Image"
					class="w-20 h-20 object-cover rounded mx-auto sm:mx-0" />
			</Link>

			<!-- Details Section -->
			<div class="flex-1">
				<Link
					:href="
						route('adkoto.show', {
							id: advertisement.id,
						})
					">
					<h3 class="text-lg text-blue-400 font-semibold">
						{{ advertisement.title }}
					</h3>
				</Link>
				<div class="flex flex-col sm:flex-row gap-2 sm:gap-4 mt-1">
					<span class="flex items-center text-xs text-gray-400">
						<UserIcon class="w-4 h-4 mr-1" />
						{{ advertisement.user.name }}
					</span>
					<span class="flex items-center text-xs text-gray-400">
						<ClockIcon class="w-4 h-4 mr-1" />
						{{ dayjs(advertisement.updated_at).fromNow() }}
					</span>
					<span class="flex items-center text-xs text-gray-400">
						<SettingsIcon class="w-4 h-4 mr-1" />
						{{ advertisement.category.name }}
					</span>
				</div>
				<p class="text-sm text-gray-500 mt-2 tracking-wide">
					{{ advertisement.description }}
				</p>
			</div>

			<!-- Price Section -->
			<div class="ml-auto flex-shrink-0">
				<span class="bg-blue-500 flex items-center text-white py-1 px-2 rounded text-xs">
					<TagIcon class="w-4 h-4 mr-1" />
					{{ advertisement.price }}
				</span>
			</div>
		</div>
	</div>
</template>

<script setup>
import UserIcon from "../Icons/UserIcon.vue";
import ClockIcon from "../Icons/ClockIcon.vue";
import SettingsIcon from "../Icons/SettingsIcon.vue";
import TagIcon from "../Icons/TagIcon.vue";
import { usePage, router, Link } from "@inertiajs/vue3";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(relativeTime);

const props = defineProps({
	advertisement: Object,
});

const deleteAd = (id) => {
	if (confirm("Are you sure you want to delete this advertisement?")) {
		router.delete(route("adkoto.destroy", { id }));
	}
};
</script>
