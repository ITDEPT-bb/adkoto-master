<script setup>
import {
	ArrowPathRoundedSquareIcon,
	ChatBubbleLeftRightIcon,
	HandThumbUpIcon,
} from "@heroicons/vue/24/outline";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import PostUserHeader from "@/Components/Tribekoto/PostUserHeader.vue";
import { router, useForm, usePage, Link } from "@inertiajs/vue3";
import axiosClient from "@/axiosClient.js";
import ReadMoreReadLess from "@/Components/Tribekoto/ReadMoreReadLess.vue";
import EditDeleteDropdown from "@/Components/Tribekoto/EditDeleteDropdown.vue";
import PostAttachments from "@/Components/Tribekoto/PostAttachments.vue";
import CommentList from "@/Components/Tribekoto/CommentList.vue";
import { computed, ref, watch } from "vue";
import UrlPreview from "@/Components/Tribekoto/UrlPreview.vue";
import { MapPinIcon } from "@heroicons/vue/24/outline/index.js";
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from "@headlessui/vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import ReactionList from "./ReactionList.vue";
import ShareIcon from "@/Components/Icons/ShareIcon.vue";
import PostAttachmentShare from "@/Components/Tribekoto/PostAttachmentShare.vue";
import BaseModal from "./BaseModal.vue";

import "emoji-picker-element";
import EmojiIcon from "@/Components/Icons/EmojiIcon.vue";
const showEmojiPicker = ref(true);

function toggleEmojiPicker() {
	showEmojiPicker.value = !showEmojiPicker.value;
}

function addEmoji(event) {
	const emoji = event.detail.unicode || event.detail.emoji;
	shareBody.value += emoji;
	showEmojiPicker.value = false;
}

const open = ref(false);

const openDeleteModal = () => {
	open.value = true;
};

const props = defineProps({
	post: Object,
});

const authUser = usePage().props.auth.user;
const group = usePage().props.group;

const emit = defineEmits(["editClick", "attachmentClick"]);

const postBody = computed(() => {
	let content = props.post.body.replace(
		/(?:(\s+)|<p>)((#\w+)(?![^<]*<\/a>))/g,
		(match, group1, group2) => {
			const encodedGroup = encodeURIComponent(group2);
			return `${group1 || ""}<a href="/search/${encodedGroup}" class="hashtag">${group2}</a>`;
		}
	);

	return content;
});
const isPinned = computed(() => {
	if (group?.id) {
		return group?.pinned_post_id === props.post.id;
	}

	return authUser?.pinned_post_id === props.post.id;
});

function openEditModal() {
	emit("editClick", props.post);
}

// function deletePost() {
//     if (window.confirm('Are you sure you want to delete this post?')) {
//         router.delete(route('post.destroy', props.post), {
//             preserveScroll: true
//         })
//     }
// }
const confirmDelete = () => {
	router
		.delete(route("post.destroy", props.post), {
			preserveScroll: true,
		})
		.then(() => {
			open.value = false;
		})
		.catch((error) => {
			console.error("Failed to delete post:", error);
		});
};

function pinUnpinPost() {
	const form = useForm({
		forGroup: group?.id,
	});
	let isPinned = false;
	if (group?.id) {
		isPinned = group?.pinned_post_id === props.post.id;
	} else {
		isPinned = authUser?.pinned_post_id === props.post.id;
	}

	form.post(route("post.pinUnpin", props.post.id), {
		preserveScroll: true,
		onSuccess: () => {
			if (group?.id) {
				group.pinned_post_id = isPinned ? null : props.post.id;
			} else {
				authUser.pinned_post_id = isPinned ? null : props.post.id;
			}
		},
	});
}

function openAttachment(ind) {
	emit("attachmentClick", props.post, ind);
}

const shareModalVisible = ref(false);
const sharePostId = ref(null);
const shareBody = ref("");
const isSharing = ref(false);

const openShareModal = (postId) => {
	sharePostId.value = postId;
	shareBody.value = "";
	shareModalVisible.value = true;
};

const setDefaultShareBody = () => {
	shareBody.value = "Check this out! 🔄";
};

watch(shareModalVisible, (newValue) => {
	if (newValue) setDefaultShareBody();
});

const submitShare = async () => {
	if (isSharing.value) return;
	isSharing.value = true;

	try {
		await axios.post(`/post/${sharePostId.value}/share`, {
			body: shareBody.value,
			preview: {},
			preview_url: null,
		});
		// alert("Post shared successfully!");
		window.location.reload();
		shareModalVisible.value = false;
		shareBody.value = "";
	} catch (error) {
		console.error(error);
	} finally {
		isSharing.value = false;
	}
};

// const showReactions = ref(false);
// const isLoading = ref(false);
// function sendReaction() {
//     isLoading.value = true;

//     axiosClient
//         .post(route("post.reaction", props.post), {
//             reaction: "like",
//         })
//         .then(({ data }) => {
//             props.post.current_user_has_reaction =
//                 data.current_user_has_reaction;
//             props.post.num_of_reactions = data.num_of_reactions;
//         })
//         .catch((error) => {
//             console.error(error);
//         })
//         .finally(() => {
//             isLoading.value = false;
//         });
// }

const showReactions = ref(false);
const isLoading = ref(false);
// const reactions = [
// 	{ name: "like", emoji: "👍", label: "Like" },
// 	{ name: "love", emoji: "❤️", label: "Love" },
// 	{ name: "haha", emoji: "😂", label: "Haha" },
// 	{ name: "wow", emoji: "😮", label: "Wow" },
// 	{ name: "sad", emoji: "😢", label: "Sad" },
// 	{ name: "angry", emoji: "😡", label: "Angry" },
// ];
const reactions = [
	{ name: "like", image: "/img/Reactions/like.png", label: "Like" },
	{ name: "love", image: "/img/Reactions/love.png", label: "Love" },
	{ name: "haha", image: "/img/Reactions/haha.png", label: "Haha" },
	{ name: "wow", image: "/img/Reactions/wow.png", label: "Wow" },
	{ name: "sad", image: "/img/Reactions/sad.png", label: "Sad" },
	{ name: "angry", image: "/img/Reactions/angry.png", label: "Angry" },
];

const getReactionIcon = (reaction) => {
	const reactionIcons = {
		like: "/img/Reactions/like.png",
		love: "/img/Reactions/love.png",
		haha: "/img/Reactions/haha.png",
		wow: "/img/Reactions/wow.png",
		sad: "/img/Reactions/sad.png",
		angry: "/img/Reactions/angry.png",
	};
	return reactionIcons[reaction] || "/img/Reactions/like.png";
};

const getReactionColor = (reaction) => {
	const reactionColors = {
		like: "text-blue-500",
		love: "text-red-500",
		haha: "text-yellow-500",
		wow: "text-green-500",
		sad: "text-purple-500",
		angry: "text-gray-500",
	};
	return reactionColors[reaction] || "text-blue-600 dark:text-gray-300";
};

const capitalizeReaction = (reaction) => {
	return reaction ? reaction.charAt(0).toUpperCase() + reaction.slice(1) : "Like";
};

// Long press detection for mobile
let pressTimer;
const startLongPress = () => {
	pressTimer = setTimeout(() => {
		showReactions.value = true;
	}, 500);
};
const cancelLongPress = () => clearTimeout(pressTimer);

// Function to send reaction
const sendReaction = (type = "like") => {
	isLoading.value = true;

	axiosClient
		.post(route("post.reaction", props.post), { reaction: type })
		.then(({ data }) => {
			props.post.current_user_has_reaction = data.current_user_has_reaction;
			props.post.num_of_reactions = data.num_of_reactions;
		})
		.catch((error) => {
			console.error(error);
		})
		.finally(() => {
			isLoading.value = false;
			showReactions.value = false;
		});
};
</script>

<template>
	<div
		class="bg-white border dark:bg-slate-950 dark:border-slate-900 dark:text-gray-100 rounded p-4 mb-3">
		<div class="flex items-center justify-between mb-3">
			<PostUserHeader :post="post" />
			<div class="flex items-center gap-2">
				<div
					v-if="isPinned"
					class="flex items-center text-xs">
					<MapPinIcon
						class="h-3 w-3"
						aria-hidden="true" />
					pinned
				</div>
				<!-- <EditDeleteDropdown
                    :user="post.user"
                    :post="post"
                    @edit="openEditModal"
                    @delete="deletePost"
                    @pin="pinUnpinPost"
                /> -->
				<EditDeleteDropdown
					:user="post.user"
					:post="post"
					@edit="openEditModal"
					@delete="openDeleteModal"
					@pin="pinUnpinPost" />
			</div>
		</div>
		<div class="mb-3">
			<ReadMoreReadLess :content="postBody" />
			<UrlPreview
				:preview="post.preview"
				:url="post.preview_url" />
		</div>
		<div
			v-if="post.shared_post"
			class="border p-3 mt-2 rounded-lg bg-gray-100">
			<PostUserHeader :post="post.shared_post" />
			<!-- <p class="text-gray-700">{{ post.shared_post.body }}</p> -->
			<ReadMoreReadLess :content="post.shared_post.body" />

			<!-- Shared Post Attachments -->
			<div
				v-if="post.shared_post.attachments && post.shared_post.attachments.length > 0"
				class="grid gap-3 m-3"
				:class="[post.shared_post.attachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2']">
				<!-- <Link :href="post.shared_post.view"> -->
				<PostAttachmentShare
					v-if="post.shared_post && post.shared_post.attachments"
					:attachments="post.shared_post.attachments"
					:view-url="post.shared_post.view"
					@attachmentClick="openAttachment" />
				<!-- </Link> -->
			</div>
		</div>

		<div
			class="grid gap-3 mb-3"
			:class="[post.attachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2']">
			<PostAttachments
				:attachments="post.attachments"
				@attachmentClick="openAttachment" />
		</div>

		<div>
			<ReactionList :postId="post.id" />
		</div>

		<Disclosure v-slot="{ open }">
			<div class="flex gap-2 relative border-t border-b my-1">
				<!-- Reaction Picker -->
				<div
					v-if="showReactions && !post.current_user_has_reaction"
					@mouseleave="showReactions = false"
					class="absolute z-10 top-10 bg-white shadow-xl rounded-full px-3 py-2 flex items-center space-x-4 transition-opacity duration-200 animate-fadeIn">
					<button
						v-for="reaction in reactions"
						:key="reaction.name"
						:disabled="loading"
						class="relative group transform transition-all duration-200 hover:scale-125"
						@click="() => sendReaction(reaction.name)">
						<img
							:src="reaction.image"
							:alt="reaction.label"
							class="w-10 h-10 rounded-full shadow-md hover:shadow-lg transition-shadow" />

						<span
							class="absolute bottom-full mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
							{{ reaction.label }}
						</span>
					</button>
				</div>

				<!-- Like/React Button -->
				<button
					@click="sendReaction()"
					@mouseenter="!post.current_user_has_reaction && (showReactions = true)"
					@touchstart="startLongPress"
					@touchend="cancelLongPress"
					class="w-full flex items-center justify-center gap-1 px-1 py-2 rounded-md transition hover:bg-gray-100 dark:hover:bg-gray-800"
					:class="[
						post.current_user_has_reaction
							? 'font-bold ' + getReactionColor(post.current_user_reaction)
							: 'text-gray-600 dark:text-gray-300',
					]"
					:disabled="isLoading">
					<HandThumbUpIcon class="w-5 h-5" />
					<span>{{ post.num_of_reactions }}</span>
					<span v-if="!isLoading">{{ post.current_user_has_reaction ? "Unlike" : "Like" }}</span>
					<span v-else>Loading...</span>
				</button>

				<!-- <button
					@click="sendReaction()"
					@mouseenter="!post.current_user_has_reaction && (showReactions = true)"
					@touchstart="startLongPress"
					@touchend="cancelLongPress"
					class="w-full flex items-center justify-center gap-1 text-gray-600 dark:text-gray-300 px-1 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition"
					:disabled="isLoading">
					<HandThumbUpIcon class="w-5 h-5" />
					<span>{{ post.num_of_reactions }}</span>
					<span v-if="!isLoading">{{ post.current_user_has_reaction ? "Unlike" : "Like" }}</span>
					<span v-else>Loading...</span>
				</button> -->

				<!-- <button
					@click="sendReaction()"
					@mouseenter="!post.current_user_has_reaction && (showReactions = true)"
					@touchstart="startLongPress"
					@touchend="cancelLongPress"
					class="w-full flex items-center justify-center gap-1 px-1 py-2 rounded-md transition hover:bg-gray-100 dark:hover:bg-gray-800"
					:class="{
						'text-gray-600 dark:text-gray-300': !post.current_user_has_reaction,
						'text-blue-500': post.current_user_reaction === 'like',
						'text-red-500': post.current_user_reaction === 'love',
						'text-yellow-500': post.current_user_reaction === 'haha',
						'text-green-500': post.current_user_reaction === 'wow',
						'text-purple-500': post.current_user_reaction === 'sad',
						'text-gray-500': post.current_user_reaction === 'angry',
					}"
					:disabled="isLoading">
					<img
						v-if="post.current_user_has_reaction"
						:src="getReactionIcon(post.current_user_reaction)"
						:alt="post.current_user_reaction"
						class="w-5 h-5" />
					<HandThumbUpIcon
						v-else
						class="w-5 h-5" />

					<span>{{ post.num_of_reactions }}</span>
					<span v-if="!isLoading">
						{{
							post.current_user_has_reaction
								? capitalizeReaction(post.current_user_reaction)
								: "Like"
						}}
					</span>
					<span v-else>Loading...</span>
				</button> -->

				<!-- Comment Button -->
				<DisclosureButton
					class="w-full flex items-center justify-center gap-1 text-gray-600 dark:text-gray-300 px-1 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition">
					<ChatBubbleLeftRightIcon class="w-5 h-5" />
					<span>{{ post.num_of_comments }}</span>
					Comment
				</DisclosureButton>

				<!-- Share Button -->
				<button
					v-if="!(post.group && post.group.group_status === 'private')"
					@click="openShareModal(post.shared_post ? post.shared_post.id : post.id)"
					:disabled="isSharing"
					class="w-full flex items-center justify-center gap-1 text-gray-600 dark:text-gray-300 px-1 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition"
					:class="{ 'opacity-50 cursor-not-allowed': isSharing }">
					<!-- <ShareIcon class="w-5 h-5" /> -->
					<ArrowPathRoundedSquareIcon class="w-5 h-5" />
					<span>{{ post.num_of_shares }}</span>
					<span v-if="isSharing">Sharing...</span>
					<span v-else>Share</span>
				</button>

				<BaseModal
					title="Share Post"
					v-model="shareModalVisible"
					@open="setDefaultShareBody">
					<div class="p-4 h-[500px]">
						<div class="relative">
							<textarea
								v-model="shareBody"
								placeholder="Add a message..."
								class="w-full p-2 border rounded-md"></textarea>
							<!-- Emoji Button -->
							<button
								@click="toggleEmojiPicker"
								class="absolute right-2 bottom-2 p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
								<EmojiIcon class="w-6 h-6 text-gray-500" />
							</button>
						</div>

						<!-- Emoji Picker -->
						<emoji-picker
							v-if="showEmojiPicker"
							class="absolute z-10 mt-2 pe-10 me-4"
							@emoji-click="addEmoji">
						</emoji-picker>

						<div class="mt-4 flex justify-end space-x-2">
							<button
								@click="shareModalVisible = false"
								class="px-4 py-2 bg-gray-300 rounded-md">
								Cancel
							</button>
							<button
								@click="submitShare"
								:disabled="isSharing"
								class="px-4 py-2 bg-blue-600 text-white rounded-md flex items-center justify-center"
								:class="{ 'opacity-50 cursor-not-allowed': isSharing }">
								<span v-if="isSharing">Sharing...</span>
								<span v-else>Share</span>
							</button>
						</div>
					</div>
				</BaseModal>
			</div>

			<DisclosurePanel class="comment-list mt-3 max-h-[400px] overflow-auto scrollbar-thin">
				<CommentList
					:post="post"
					:data="{ comments: post.comments }" />
			</DisclosurePanel>
		</Disclosure>

		<!-- Delete Modal -->
		<TransitionRoot
			as="template"
			:show="open">
			<Dialog
				class="relative z-10"
				@close="open = false">
				<TransitionChild
					as="template"
					enter="ease-out duration-300"
					enter-from="opacity-0"
					enter-to="opacity-100"
					leave="ease-in duration-200"
					leave-from="opacity-100"
					leave-to="opacity-0">
					<div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
				</TransitionChild>

				<div class="fixed inset-0 z-10 w-screen overflow-y-auto scrollbar-thin">
					<div
						class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
						<TransitionChild
							as="template"
							enter="ease-out duration-300"
							enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
							enter-to="opacity-100 translate-y-0 sm:scale-100"
							leave="ease-in duration-200"
							leave-from="opacity-100 translate-y-0 sm:scale-100"
							leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
							<DialogPanel
								class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
								<div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
									<div class="sm:flex sm:items-start">
										<div
											class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
											<ExclamationTriangleIcon
												class="h-6 w-6 text-red-600"
												aria-hidden="true" />
										</div>
										<div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
											<DialogTitle
												as="h3"
												class="text-base font-semibold leading-6 text-gray-900">
												Delete Post
											</DialogTitle>
											<div class="mt-2">
												<p class="text-sm text-gray-500">
													Are you sure you want to delete this post?
												</p>
											</div>
										</div>
									</div>
								</div>
								<div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
									<button
										type="button"
										class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
										@click="confirmDelete">
										Delete
									</button>
									<button
										type="button"
										class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
										@click="open = false"
										ref="cancelButtonRef">
										Cancel
									</button>
								</div>
							</DialogPanel>
						</TransitionChild>
					</div>
				</div>
			</Dialog>
		</TransitionRoot>
	</div>
</template>

<style scoped>
.reaction-picker {
	display: flex;
	gap: 1px;
	background: white;
	padding: 5px;
	border-radius: 5px;
}

.reaction-popup {
	display: flex;
	gap: 8px;
	background: white;
	border: 1px solid #ccc;
	border-radius: 20px;
	padding: 8px;
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
	position: absolute;
	z-index: 10;
}
</style>
