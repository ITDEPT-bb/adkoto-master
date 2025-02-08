<script setup>
import { Link } from "@inertiajs/vue3";
import { ChevronRightIcon } from "@heroicons/vue/24/solid/index.js";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(relativeTime);

defineProps({
	post: {
		type: Object,
	},
	showTime: {
		type: Boolean,
		default: true,
	},
});

// function formatTime(postDate) {
// 	const now = dayjs();
// 	const postTime = dayjs(postDate);

// 	if (now.diff(postTime, "week") >= 1) {
// 		return postTime.format("MMMM D, YYYY");
// 	} else {
// 		return postTime.fromNow();
// 	}
// }
function formatTime(postDate) {
	const now = dayjs();
	const postTime = dayjs(postDate);

	if (now.diff(postTime, "day") < 1) {
		return postTime.fromNow();
	} else if (now.diff(postTime, "day") === 1) {
		return `Yesterday at ${postTime.format("h:mm A")}`;
	}
	// else if (now.diff(postTime, "day") < 7) {
	// 	return `${postTime.fromNow(true)} ago at ${postTime.format("h:mm A")}`;
	// }
	else {
		return postTime.format("MMMM D, YYYY [at] h:mm A");
	}
}
</script>

<template>
	<div class="flex items-center gap-2">
		<Link :href="route('profile', post.user.username)">
			<img
				:src="post.user.avatar_url"
				class="w-10 h-10 object-cover rounded-full border-2 transition-all hover:border-red-500" />
		</Link>
		<div>
			<h4 class="flex items-center font-bold">
				<Link
					:href="route('profile', post.user.username)"
					class="hover:underline">
					{{ post.user.name }} {{ post.user.surname }}
				</Link>
				<template v-if="post.group">
					<ChevronRightIcon class="w-4" />
					<Link
						:href="route('group.profile', post.group.slug)"
						class="hover:underline">
						{{ post.group.name }}
					</Link>
				</template>
			</h4>
			<small
				v-if="showTime"
				class="text-gray-400"
				>{{ formatTime(post.created_at) }}</small
			>
		</div>
	</div>
</template>

<style scoped></style>
