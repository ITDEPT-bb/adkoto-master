<script setup>
import { computed, ref } from "vue";
import { XMarkIcon, CheckCircleIcon, CameraIcon } from "@heroicons/vue/24/solid";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
import { usePage, Link, Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TabItem from "@/Pages/Profile/Partials/TabItem.vue";
import Edit from "@/Pages/Profile/Edit.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import DangerButton from "@/Components/DangerButton.vue";
import CreatePost from "@/Components/Tribekoto/CreatePost.vue";
import PostList from "@/Components/Tribekoto/PostList.vue";
import UserListItem from "@/Components/Tribekoto/UserListItem.vue";
import TextInput from "@/Components/TextInput.vue";
import PostAttachments from "@/Components/Tribekoto/PostAttachments.vue";
import TabPhotos from "@/Pages/Profile/TabPhotos.vue";
import FollowingList from "@/Components/Tribekoto/FollowingList.vue";
import ProfileCoverModal from "@/Components/Tribekoto/ProfileCoverModal.vue";
import ProfileAvatarModal from "@/Components/Tribekoto/ProfileAvatarModal.vue";

import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

import followIcon from "/public/img/icons/heartfollow.png";
import unfollowIcon from "/public/img/icons/heartunfollow.png";
import MessageIcon from "@/Components/Icons/MessageIcon.vue";

// const imagesForm = useForm({
//     avatar: null,
//     cover: null,
// });

const showNotification = ref(true);
// const coverImageSrc = ref("");
// const avatarImageSrc = ref("");
const searchFollowersKeyword = ref("");
const searchFollowingsKeyword = ref("");
const authUser = usePage().props.auth.user;

const isMyProfile = computed(() => authUser && authUser.id === props.user.id);
const isFollowersActive = ref(true);

const isOpen = ref(false);
const openModal = () => {
	isOpen.value = true;
};
const closeModal = () => {
	isOpen.value = false;
};

const isOpenAvatar = ref(false);
const openModalAvatar = () => {
	isOpenAvatar.value = true;
};
const closeModalAvatar = () => {
	isOpenAvatar.value = false;
};

const props = defineProps({
	errors: Object,
	mustVerifyEmail: {
		type: Boolean,
	},
	status: {
		type: String,
	},
	success: {
		type: String,
	},
	isCurrentUserFollower: Boolean,
	isPrivate: Boolean,
	followRequestSent: Boolean,
	followRequestPending: Boolean,
	followRequest: Boolean,
	followerCount: Number,
	user: {
		type: Object,
	},
	posts: Object,
	followers: Array,
	PendingFollowers: Array,
	followings: Array,
	photos: Array,
});

// function onCoverChange(event) {
//     imagesForm.cover = event.target.files[0];
//     if (imagesForm.cover) {
//         const reader = new FileReader();
//         reader.onload = () => {
//             coverImageSrc.value = reader.result;
//         };
//         reader.readAsDataURL(imagesForm.cover);
//     }
// }

// function onAvatarChange(event) {
//     imagesForm.avatar = event.target.files[0];
//     if (imagesForm.avatar) {
//         const reader = new FileReader();
//         reader.onload = () => {
//             avatarImageSrc.value = reader.result;
//         };
//         reader.readAsDataURL(imagesForm.avatar);
//     }
// }

// function resetCoverImage() {
//     imagesForm.cover = null;
//     coverImageSrc.value = null;
// }

// function resetAvatarImage() {
//     imagesForm.avatar = null;
//     avatarImageSrc.value = null;
// }

// function submitCoverImage() {
//     imagesForm.post(route("profile.updateImages"), {
//         preserveScroll: true,
//         onSuccess: (user) => {
//             showNotification.value = true;
//             resetCoverImage();
//             setTimeout(() => {
//                 showNotification.value = false;
//             }, 3000);
//         },
//     });
// }

// function submitAvatarImage() {
//     imagesForm.post(route("profile.updateImages"), {
//         preserveScroll: true,
//         onSuccess: (user) => {
//             showNotification.value = true;
//             resetAvatarImage();
//             setTimeout(() => {
//                 showNotification.value = false;
//             }, 3000);
//         },
//     });
// }

const isLoading = ref(false);
// function followUser() {
// 	isLoading.value = true;

// 	const form = useForm({
// 		follow: !props.isCurrentUserFollower,
// 	});

// 	form.post(route("user.follow", props.user.id), {
// 		preserveScroll: true,
// 		onFinish: () => {
// 			isLoading.value = false;
// 		},
// 	});
// }
function followUser() {
	isLoading.value = true;

	const form = useForm({
		follow: true,
	});

	form.post(route("user.follow", props.user.id), {
		preserveScroll: true,
		onFinish: () => {
			isLoading.value = false;
		},
	});
}

function unfollowUser() {
	isLoading.value = true;

	const form = useForm({
		follow: false,
	});

	form.post(route("user.follow", props.user.id), {
		preserveScroll: true,
		onFinish: () => {
			isLoading.value = false;
		},
	});
}

function acceptFollowRequest(userId) {
	isLoading.value = true;

	const form = useForm({
		follow: false,
	});

	form.post(route("user.accept", userId), {
		preserveScroll: true,
		onFinish: () => {
			isLoading.value = false;
		},
	});
}

function rejectFollowRequest(userId) {
	isLoading.value = true;

	const form = useForm({
		follow: false,
	});

	form.post(route("user.reject", userId), {
		preserveScroll: true,
		onFinish: () => {
			isLoading.value = false;
		},
	});
}

function cancelFollowRequest() {
	unfollowUser();
}
</script>

<template>
	<Head title="Profile" />

	<AuthenticatedLayout>
		<div class="mx-auto max-w-7xl bg-white h-full overflow-auto scrollbar-thin">
			<div class="px-4">
				<div
					v-show="showNotification && success"
					class="my-2 py-2 px-3 font-medium text-sm bg-emerald-500 text-white">
					{{ success }}
				</div>
				<div
					v-if="errors.cover"
					class="my-2 py-2 px-3 font-medium text-sm bg-red-400 text-white">
					{{ errors.cover }}
				</div>

				<div class="group relative bg-white dark:bg-slate-950 dark:text-gray-100">
					<!-- <img
                        :src="user.cover_url || '/img/default_cover.jpg'"
                        class="w-full h-56 sm:h-[400px] object-cover"
                    /> -->
					<div v-if="isMyProfile">
						<img
							:src="user.cover_url || '/img/default_cover.jpg'"
							class="w-full aspect-[3/1] min-h-[150px] max-h-[400px] object-cover hover:opacity-90 cursor-pointer"
							@click="openModal" />
					</div>
					<div v-else>
						<img
							:src="user.cover_url || '/img/default_cover.jpg'"
							class="w-full aspect-[3/1] min-h-[150px] max-h-[400px] object-cover hover:opacity-90 cursor-pointer" />
					</div>

					<!-- Modal Component -->
					<ProfileCoverModal
						:imageSrc="user.cover_url || '/img/default_cover.jpg'"
						:isOpen="isOpen"
						@close="closeModal" />
					<!-- <div v-if="isMyProfile" class="absolute top-2 right-2">
                        <button
                            v-if="!coverImageSrc"
                            class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center opacity-0 group-hover:opacity-100"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-3 h-3 mr-2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"
                                />
                            </svg>

                            Update Cover Image
                            <input
                                type="file"
                                class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                                @change="onCoverChange"
                            />
                        </button>
                        <div
                            v-else
                            class="flex gap-2 bg-white p-2 opacity-0 group-hover:opacity-100"
                        >
                            <button
                                @click="resetCoverImage"
                                class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center"
                            >
                                <XMarkIcon class="h-3 w-3 mr-2" />
                                Cancel
                            </button>
                            <button
                                @click="submitCoverImage"
                                class="bg-gray-800 hover:bg-gray-900 text-gray-100 py-1 px-2 text-xs flex items-center"
                            >
                                <CheckCircleIcon class="h-3 w-3 mr-2" />
                                Submit
                            </button>
                        </div>
                    </div> -->

					<!-- <div class="flex flex-col md:flex-row"> -->
					<div class="flex flex-row md:flex-row">
						<div
							class="flex items-center justify-center relative group/avatar mt-[-32px] md:-mt-[64px] ml-[10px] w-[96px] h-[96px] md:w-[128px] md:h-[128px] rounded-full">
							<div v-if="isMyProfile">
								<img
									:src="user.avatar_url || '/img/default_avatar.png'"
									class="w-24 h-24 sm:w-full sm:h-full object-cover rounded-full hover:opacity-95 hover:cursor-pointer"
									@click="openModalAvatar" />
							</div>
							<div v-else>
								<img
									:src="user.avatar_url || '/img/default_avatar.png'"
									class="w-24 h-24 sm:w-full sm:h-full object-cover rounded-full hover:opacity-95 hover:cursor-pointer" />
							</div>

							<ProfileAvatarModal
								:imageSrc="user.avatar_url || '/img/default_avatar.jpg'"
								:isOpenAvatar="isOpenAvatar"
								@close="closeModalAvatar" />

							<!-- <button
                                v-if="!avatarImageSrc"
                                class="absolute left-0 top-0 right-0 bottom-0 bg-black/50 text-gray-200 rounded-full opacity-0 flex items-center justify-center group-hover/avatar:opacity-100"
                            >
                                <template v-if="isMyProfile">
                                    <CameraIcon class="w-8 h-8" />
                                    <input
                                        type="file"
                                        class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                                        @change="onAvatarChange"
                                    />
                                </template>
                            </button>
                            <div
                                v-else
                                class="absolute top-1 right-0 flex flex-col gap-2"
                            >
                                <button
                                    @click="resetAvatarImage"
                                    class="w-7 h-7 flex items-center justify-center bg-red-500/80 text-white rounded-full"
                                >
                                    <XMarkIcon class="h-5 w-5" />
                                </button>
                                <button
                                    @click="submitAvatarImage"
                                    class="w-7 h-7 flex items-center justify-center bg-emerald-500/80 text-white rounded-full"
                                >
                                    <CheckCircleIcon class="h-5 w-5" />
                                </button>
                            </div> -->
						</div>
						<div class="flex justify-between items-center flex-1 px-2 py-1 sm:px-4 sm:py-2">
							<div>
								<h2 class="font-bold text-lg text-nowrap">{{ user.name }} {{ user.surname }}</h2>
								<p class="text-sm text-gray-500 mb-1">@{{ user.username }}</p>
								<p class="text-xs text-gray-500 ps-1">{{ followerCount }} follower(s)</p>
							</div>

							<div
								v-if="!isMyProfile"
								class="flex mt-6 me-1 gap-3 sm:me-0 sm:mt-0">
								<Link :href="`/chat/conversation/adktu/${user.id}`">
									<button
										type="button"
										class="inline-flex items-center justify-center rounded-lg p-2 h-8 w-auto transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
										<MessageIcon />
									</button>
								</Link>
								<!-- <PrimaryButton
									v-if="!isCurrentUserFollower && !followRequestSent && isPrivate"
									@click="followUser"
									:disabled="isLoading"
									:class="{
										'bg-gray-300 cursor-not-allowed': isLoading,
										'bg-blue-500': !isLoading,
									}">
									<template v-if="!isLoading">
										<img
											:src="followIcon"
											class="h-6 w-auto flex sm:hidden"
											alt="FollowIcon" />
										<p class="hidden sm:flex">Follow</p>
									</template>
									<p
										v-else
										class="hidden sm:flex">
										Loading...
									</p>
								</PrimaryButton>
								<SecondaryButton
									v-else-if="followRequestSent"
									@click="followUser"
									:disabled="isLoading"
									:class="{
										'bg-gray-300 cursor-not-allowed': isLoading,
										'bg-red-500': !isLoading,
									}"
									><template v-if="!isLoading">
										<img
											:src="unfollowIcon"
											class="h-6 w-auto flex sm:hidden"
											alt="UnfollowIcon" />
										<p class="hidden sm:flex">Request Pending</p>
									</template>
									<p
										v-else
										class="hidden sm:flex">
										Loading...
									</p></SecondaryButton
								>
								<DangerButton
									v-else-if="isCurrentUserFollower"
									@click="followUser"
									:disabled="isLoading"
									:class="{
										'bg-gray-300 cursor-not-allowed': isLoading,
										'bg-red-500': !isLoading,
									}">
									<template v-if="!isLoading">
										<img
											:src="unfollowIcon"
											class="h-6 w-auto flex sm:hidden"
											alt="UnfollowIcon" />
										<p class="hidden sm:flex">Unfollow</p>
									</template>
									<p
										v-else
										class="hidden sm:flex">
										Loading...
									</p>
								</DangerButton> -->
								<PrimaryButton
									v-if="!isCurrentUserFollower && !followRequestSent"
									@click="followUser"
									:disabled="isLoading"
									:class="{
										'bg-gray-300 cursor-not-allowed': isLoading,
										'bg-blue-500': !isLoading,
									}">
									<template v-if="!isLoading">
										<p class="hidden sm:flex">Follow</p>
									</template>
									<p
										v-else
										class="hidden sm:flex">
										Loading...
									</p>
								</PrimaryButton>

								<SecondaryButton
									v-else-if="followRequestSent"
									@click="cancelFollowRequest"
									:disabled="isLoading"
									:class="{
										'bg-gray-300 cursor-not-allowed': isLoading,
										'bg-red-500': !isLoading,
									}">
									<template v-if="!isLoading">
										<p class="hidden sm:flex">Request Pending</p>
									</template>
									<p
										v-else
										class="hidden sm:flex">
										Loading...
									</p>
								</SecondaryButton>

								<DangerButton
									v-else-if="isCurrentUserFollower"
									@click="unfollowUser"
									:disabled="isLoading"
									:class="{
										'bg-gray-300 cursor-not-allowed': isLoading,
										'bg-red-500': !isLoading,
									}">
									<template v-if="!isLoading">
										<p class="hidden sm:flex">Unfollow</p>
									</template>
									<p
										v-else
										class="hidden sm:flex">
										Loading...
									</p>
								</DangerButton>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="border-t p-4 pt-0"> -->
			<div class="border-t m-4 mt-0">
				<TabGroup>
					<TabList class="flex bg-white dark:bg-slate-950 dark:text-white">
						<Tab
							v-slot="{ selected }"
							as="template">
							<TabItem
								text="Posts"
								:selected="selected" />
						</Tab>
						<Tab
							v-slot="{ selected }"
							as="template">
							<TabItem
								text="Followers"
								:selected="selected" />
						</Tab>
						<Tab
							v-slot="{ selected }"
							as="template">
							<TabItem
								text="Followings"
								:selected="selected" />
						</Tab>
						<Tab
							v-slot="{ selected }"
							as="template">
							<TabItem
								text="Photos"
								:selected="selected" />
						</Tab>
						<Tab
							v-if="isMyProfile"
							v-slot="{ selected }"
							as="template">
							<TabItem
								text="My Profile"
								:selected="selected" />
						</Tab>
					</TabList>

					<TabPanels class="mt-2">
						<TabPanel>
							<div class="bg-gray-100">
								<div class="grid max-w-7xl mx-auto lg:grid-cols-12 gap-3 p-4 h-auto">
									<!-- <div
                                        class="lg:col-span-4 lg:order-1 h-full overflow-hidden"
                                    >
                                        <FollowingList :users="followings" />
                                    </div> -->
									<!-- <div
										class="lg:col-span-12 lg:order-2 h-auto w-full mx-auto overflow-hidden lg:px-10 flex flex-col">
										<template v-if="posts">
											<template v-if="isMyProfile">
												<CreatePost />
											</template>
											<PostList
												:posts="posts.data"
												class="flex-1" />
										</template>
										<div
											v-else
											class="py-8 text-center dark:text-gray-100">
											You don't have permission to view these posts.
										</div>
									</div> -->
									<div
										class="lg:col-span-12 lg:order-2 h-auto w-full mx-auto overflow-hidden lg:px-10 flex flex-col">
										<template v-if="!isPrivate || isMyProfile || isCurrentUserFollower">
											<!-- Check if posts exist -->
											<template v-if="posts">
												<template v-if="isMyProfile">
													<CreatePost />
												</template>
												<PostList
													:posts="posts.data"
													class="flex-1" />
											</template>
											<!-- No posts available -->
											<div
												v-else
												class="py-8 text-center dark:text-gray-100">
												No posts available.
											</div>
										</template>

										<!-- If the profile is private and the user doesn't have access -->
										<div
											v-else
											class="py-8 text-center dark:text-gray-100">
											You don't have permission to view these posts.
										</div>
									</div>
								</div>
							</div>
						</TabPanel>
						<!-- <TabPanel>
							<template v-if="!isPrivate || isMyProfile || isCurrentUserFollower">
								<div class="mb-3">
									<TextInput
										:model-value="searchFollowersKeyword"
										placeholder="Type to search"
										class="w-full" />
								</div>
								<div
									v-if="followers.length"
									class="grid grid-cols-2 gap-3">
									<UserListItem
										v-for="user of followers"
										:user="user"
										:key="user.id"
										class="shadow rounded-lg" />
								</div>
								<div
									v-else
									class="text-center py-8">
									User does not have followers.
								</div>
							</template>
							<div
								v-else
								class="py-8 text-center dark:text-gray-100">
								You don't have permission to view this user's followers.
							</div>
						</TabPanel> -->
						<TabPanel>
							<template
								v-if="!isPrivate || isMyProfile || isCurrentUserFollower"
								class="px-10">
								<div class="flex mb-6 pt-5">
									<!-- Followers Tab Button -->
									<button
										:class="{
											'bg-blue-500 text-white': isFollowersActive,
											'bg-gray-300 text-gray-800': !isFollowersActive,
										}"
										@click="isFollowersActive = true"
										class="px-4 py-2 rounded-l-lg focus:outline-none">
										Followers
									</button>

									<!-- Pending Followers Tab Button -->
									<button
										:class="{
											'bg-blue-500 text-white': !isFollowersActive,
											'bg-gray-300 text-gray-800': isFollowersActive,
										}"
										@click="isFollowersActive = false"
										class="px-4 py-2 rounded-r-lg focus:outline-none">
										Pending Followers
									</button>
								</div>

								<div class="mb-3">
									<TextInput
										:model-value="searchFollowersKeyword"
										placeholder="Type to search"
										class="w-full" />
								</div>

								<div
									v-if="isFollowersActive"
									class="flex-1 px-1">
									<div
										v-if="followers.length"
										class="grid grid-cols-2 gap-3">
										<UserListItem
											v-for="user of followers"
											:user="user"
											:key="user.id"
											class="shadow rounded-lg" />
									</div>
									<div
										v-else
										class="text-center py-8">
										User does not have followers.
									</div>
								</div>

								<div
									v-else
									class="flex-1 px-1">
									<div
										v-if="PendingFollowers.length"
										class="mb-6">
										<!-- <h3 class="text-lg font-medium mb-2">Pending Follow Requests</h3> -->
										<div class="grid grid-cols-2 gap-3">
											<UserListItem
												v-for="user of PendingFollowers"
												:key="user.id"
												:user="user"
												class="shadow rounded-lg">
												<template v-slot:actions>
													<div class="flex gap-6">
														<!-- Accept Follow Request Button -->
														<PrimaryButton
															@click="acceptFollowRequest(user.id)"
															:disabled="isLoading || user.isAccepted"
															:class="{
																'bg-gray-300 cursor-not-allowed': isLoading || user.isAccepted,
																'bg-green-500': !isLoading && !user.isAccepted,
															}">
															<template v-if="!isLoading && !user.isAccepted">Accept</template>
															<p
																v-else
																class="hidden sm:flex">
																Loading...
															</p>
														</PrimaryButton>

														<!-- Reject Follow Request Button -->
														<PrimaryButton
															@click="rejectFollowRequest(user.id)"
															:disabled="isLoading || user.isAccepted"
															:class="{
																'bg-gray-300 cursor-not-allowed': isLoading || user.isAccepted,
																'bg-red-500': !isLoading && !user.isAccepted,
															}">
															<template v-if="!isLoading && !user.isAccepted">Reject</template>
															<p
																v-else
																class="hidden sm:flex">
																Loading...
															</p>
														</PrimaryButton>
													</div>
												</template>
											</UserListItem>
										</div>
									</div>
									<div
										v-else
										class="text-center py-8">
										No pending follow requests.
									</div>
								</div>
							</template>
							<div
								v-else
								class="py-8 text-center dark:text-gray-100">
								You don't have permission to view this user's followers.
							</div>

							<!-- Pending Follow Requests List -->
							<!-- <div
								v-if="PendingFollowers.length"
								class="mb-6">
								<h3 class="text-lg font-medium mb-2">Pending Follow Requests</h3>
								<div class="grid grid-cols-2 gap-3">
									<UserListItem
										v-for="user of PendingFollowers"
										:key="user.id"
										:user="user"
										class="shadow rounded-lg">
										<template v-slot:actions>
											<PrimaryButton
												@click="acceptFollowRequest(user.id)"
												:disabled="isLoading"
												:class="{
													'bg-gray-300 cursor-not-allowed': isLoading,
													'bg-green-500': !isLoading,
												}">
												<template v-if="!isLoading">Accept Request</template>
												<p
													v-else
													class="hidden sm:flex">
													Loading...
												</p>
											</PrimaryButton>
										</template>
									</UserListItem>
								</div>
							</div>
							<div
								v-else
								class="text-center py-8">
								No pending follow requests.
							</div> -->
						</TabPanel>
						<TabPanel>
							<template v-if="!isPrivate || isMyProfile || isCurrentUserFollower">
								<div class="mb-3">
									<TextInput
										:model-value="searchFollowingsKeyword"
										placeholder="Type to search"
										class="w-full" />
								</div>
								<div
									v-if="followings.length"
									class="grid grid-cols-2 gap-3">
									<UserListItem
										v-for="user of followings"
										:user="user"
										:key="user.id"
										class="shadow rounded-lg" />
								</div>
								<div
									v-else
									class="text-center py-8">
									The user is not following to anybody
								</div>
							</template>
							<div
								v-else
								class="py-8 text-center dark:text-gray-100">
								You don't have permission to view this user's followings.
							</div>
						</TabPanel>
						<TabPanel>
							<template v-if="!isPrivate || isMyProfile || isCurrentUserFollower">
								<TabPhotos :photos="photos" />
							</template>
							<div
								v-else
								class="py-8 text-center dark:text-gray-100">
								You don't have permission to view this user's photos.
							</div>
						</TabPanel>
						<TabPanel v-if="isMyProfile">
							<Edit
								:must-verify-email="mustVerifyEmail"
								:status="status" />
						</TabPanel>
					</TabPanels>
				</TabGroup>
			</div>
		</div>
	</AuthenticatedLayout>
	<UpdateProfileReminder />
</template>

<style scoped></style>
