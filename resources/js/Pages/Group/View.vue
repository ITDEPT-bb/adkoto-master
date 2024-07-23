<script setup>
import { computed, ref } from "vue";
import {
    XMarkIcon,
    CheckCircleIcon,
    CameraIcon,
} from "@heroicons/vue/24/solid";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
import { usePage, Head, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TabItem from "@/Pages/Profile/Partials/TabItem.vue";
import { useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InviteUserModal from "@/Pages/Group/InviteUserModal.vue";
import UserListItem from "@/Components/Tribekoto/UserListItem.vue";
import TextInput from "@/Components/TextInput.vue";
import GroupForm from "@/Components/Tribekoto/GroupForm.vue";
import PostList from "@/Components/Tribekoto/PostList.vue";
import CreatePost from "@/Components/Tribekoto/CreatePost.vue";
import TabPhotos from "@/Pages/Profile/TabPhotos.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import axiosClient from "@/axiosClient.js";

import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";

const imagesForm = useForm({
    thumbnail: null,
    cover: null,
});

const showNotification = ref(true);
const coverImageSrc = ref("");
const thumbnailImageSrc = ref("");
const showInviteUserModal = ref(false);
const searchKeyword = ref("");

const authUser = usePage().props.auth.user;

const isCurrentUserAdmin = computed(() => props.group.role === "admin");
const isJoinedToGroup = computed(
    () => props.group.role && props.group.status === "approved"
);

const props = defineProps({
    errors: Object,
    success: {
        type: String,
    },
    group: {
        type: Object,
    },
    posts: Object,
    users: Array,
    requests: Array,
    photos: Array,
});

const open = ref(false);
const openDeleteModalAds = () => {
    open.value = true;
};

const aboutForm = useForm({
    name: usePage().props.group.name,
    auto_approval: !!parseInt(usePage().props.group.auto_approval),
    about: usePage().props.group.about,
});

function onCoverChange(event) {
    imagesForm.cover = event.target.files[0];
    if (imagesForm.cover) {
        const reader = new FileReader();
        reader.onload = () => {
            coverImageSrc.value = reader.result;
        };
        reader.readAsDataURL(imagesForm.cover);
    }
}

function updateGroup() {
    aboutForm.put(route("group.update", props.group.slug), {
        preserveScroll: true,
    });
}

function onThumbnailChange(event) {
    imagesForm.thumbnail = event.target.files[0];
    if (imagesForm.thumbnail) {
        const reader = new FileReader();
        reader.onload = () => {
            thumbnailImageSrc.value = reader.result;
        };
        reader.readAsDataURL(imagesForm.thumbnail);
    }
}

function resetCoverImage() {
    imagesForm.cover = null;
    coverImageSrc.value = null;
}

function resetThurmbnailImage() {
    imagesForm.thumbnail = null;
    thumbnailImageSrc.value = null;
}

function submitCoverImage() {
    imagesForm.post(route("group.updateImages", props.group.slug), {
        preserveScroll: true,
        onSuccess: () => {
            showNotification.value = true;
            resetCoverImage();
            setTimeout(() => {
                showNotification.value = false;
            }, 3000);
        },
    });
}

function submitThurmbnailImage() {
    imagesForm.post(route("group.updateImages", props.group.slug), {
        preserveScroll: true,
        onSuccess: () => {
            showNotification.value = true;
            resetThurmbnailImage();
            setTimeout(() => {
                showNotification.value = false;
            }, 3000);
        },
    });
}

function joinToGroup() {
    const form = useForm({});

    form.post(route("group.join", props.group.slug), {
        preserveScroll: true,
    });
}

function approveUser(user) {
    const form = useForm({
        user_id: user.id,
        action: "approve",
    });
    form.post(route("group.approveRequest", props.group.slug), {
        preserveScroll: true,
    });
}

function rejectUser(user) {
    const form = useForm({
        user_id: user.id,
        action: "reject",
    });
    form.post(route("group.approveRequest", props.group.slug), {
        preserveScroll: true,
    });
}

function deleteUser(user) {
    console.log("111");
    if (
        !window.confirm(
            `Are you sure you want to remove user "${user.name}" from this group?`
        )
    ) {
        return false;
    }
    const form = useForm({
        user_id: user.id,
    });
    form.delete(route("group.removeUser", props.group.slug), {
        preserveScroll: true,
    });
}

function onRoleChange(user, role) {
    console.log(user, role);
    const form = useForm({
        user_id: user.id,
        role,
    });
    form.post(route("group.changeRole", props.group.slug), {
        preserveScroll: true,
    });
}

// const deleteGroup = async () => {
//     if (confirm("Are you sure you want to delete this group?")) {
//         try {
//             await axiosClient.delete(`/group/${props.group.id}/delete`);
//             window.location.reload();
//         } catch (error) {
//             console.error("Failed to delete the group:", error);
//         }
//     }
// };

const confirmDelete = () => {
    router
        .delete(route("group.delete", props.group.id), {
            preserveScroll: true,
        })
        .then(() => {
            open.value = false;
        })
        .catch((error) => {
            console.error("Failed to delete group:", error);
        });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Group" />
        <!-- <div class="max-w-[768px] mx-auto h-full overflow-auto"> -->
        <div class="mx-auto h-full overflow-auto">
            <div class="px-4">
                <div
                    v-show="showNotification && success"
                    class="my-2 py-2 px-3 font-medium text-sm bg-emerald-500 text-white"
                >
                    {{ success }}
                </div>
                <div
                    v-if="errors.cover"
                    class="my-2 py-2 px-3 font-medium text-sm bg-red-400 text-white"
                >
                    {{ errors.cover }}
                </div>
                <div
                    class="group relative bg-white dark:bg-slate-950 dark:text-gray-100"
                >
                    <img
                        :src="
                            coverImageSrc ||
                            group.cover_url ||
                            '/img/default_cover.jpg'
                        "
                        class="w-full h-[200px] object-cover"
                    />
                    <div
                        v-if="isCurrentUserAdmin"
                        class="absolute top-2 right-2"
                    >
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
                    </div>

                    <div class="flex">
                        <div
                            class="flex items-center justify-center relative group/thumbnail -mt-[64px] ml-[48px] w-[128px] h-[128px] rounded-full"
                        >
                            <img
                                :src="
                                    thumbnailImageSrc ||
                                    group.thumbnail_url ||
                                    '/img/default_avatar.webp'
                                "
                                class="w-full h-full object-cover rounded-full"
                            />
                            <button
                                v-if="isCurrentUserAdmin && !thumbnailImageSrc"
                                class="absolute left-0 top-0 right-0 bottom-0 bg-black/50 text-gray-200 rounded-full opacity-0 flex items-center justify-center group-hover/thumbnail:opacity-100"
                            >
                                <CameraIcon class="w-8 h-8" />

                                <input
                                    type="file"
                                    class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                                    @change="onThumbnailChange"
                                />
                            </button>

                            <div
                                v-else-if="isCurrentUserAdmin"
                                class="absolute top-1 right-0 flex flex-col gap-2"
                            >
                                <button
                                    @click="resetThurmbnailImage"
                                    class="w-7 h-7 flex items-center justify-center bg-red-500/80 text-white rounded-full"
                                >
                                    <XMarkIcon class="h-5 w-5" />
                                </button>
                                <button
                                    @click="submitThurmbnailImage"
                                    class="w-7 h-7 flex items-center justify-center bg-emerald-500/80 text-white rounded-full"
                                >
                                    <CheckCircleIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </div>
                        <div
                            class="flex justify-between items-center flex-1 p-4"
                        >
                            <h2 class="font-bold text-lg">{{ group.name }}</h2>

                            <PrimaryButton
                                v-if="!authUser"
                                :href="route('login')"
                            >
                                Login to join to this group
                            </PrimaryButton>

                            <PrimaryButton
                                v-if="isCurrentUserAdmin"
                                @click="showInviteUserModal = true"
                            >
                                Invite Users
                            </PrimaryButton>
                            <PrimaryButton
                                v-if="
                                    authUser &&
                                    !group.role &&
                                    group.auto_approval
                                "
                                @click="joinToGroup"
                            >
                                Join to Group
                            </PrimaryButton>
                            <PrimaryButton
                                v-if="
                                    authUser &&
                                    !group.role &&
                                    !group.auto_approval
                                "
                                @click="joinToGroup"
                            >
                                Request to join
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t m-4 mt-0">
                <TabGroup>
                    <TabList
                        class="flex bg-white dark:bg-slate-950 dark:text-white"
                    >
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Posts" :selected="selected" />
                        </Tab>
                        <Tab
                            v-if="isJoinedToGroup"
                            v-slot="{ selected }"
                            as="template"
                        >
                            <TabItem text="Users" :selected="selected" />
                        </Tab>
                        <Tab
                            v-if="isCurrentUserAdmin"
                            v-slot="{ selected }"
                            as="template"
                        >
                            <TabItem
                                text="Pending Requests"
                                :selected="selected"
                            />
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Photos" :selected="selected" />
                        </Tab>
                        <!-- <Tab v-if="isCurrentUserAdmin" v-slot="{ selected }" as="template"> -->
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="About" :selected="selected" />
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">
                        <TabPanel>
                            <template v-if="posts">
                                <CreatePost :group="group" />
                                <PostList
                                    v-if="posts.data.length"
                                    :posts="posts.data"
                                    class="flex-1"
                                />
                                <div
                                    v-else
                                    class="py-8 text-center dark:text-gray-100"
                                >
                                    There are no posts in this group. Be the
                                    first and create it.
                                </div>
                            </template>
                            <div
                                v-else
                                class="py-8 text-center dark:text-gray-100"
                            >
                                You don't have permission to view these posts.
                            </div>
                        </TabPanel>
                        <TabPanel v-if="isJoinedToGroup">
                            <div class="mb-3">
                                <TextInput
                                    :model-value="searchKeyword"
                                    placeholder="Type to search"
                                    class="w-full"
                                />
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <UserListItem
                                    v-for="user of users"
                                    :user="user"
                                    :key="user.id"
                                    :show-role-dropdown="isCurrentUserAdmin"
                                    :disable-role-dropdown="
                                        group.user_id === user.id
                                    "
                                    class="shadow rounded-lg"
                                    @role-change="onRoleChange"
                                    @delete="deleteUser"
                                />
                            </div>
                        </TabPanel>
                        <TabPanel v-if="isCurrentUserAdmin" class="">
                            <div
                                v-if="requests.length"
                                class="grid grid-cols-2 gap-3"
                            >
                                <UserListItem
                                    v-for="user of requests"
                                    :user="user"
                                    :key="user.id"
                                    :for-approve="true"
                                    class="shadow rounded-lg"
                                    @approve="approveUser"
                                    @reject="rejectUser"
                                />
                            </div>
                            <div class="py-8 text-center dark:text-gray-100">
                                There are no pending requests.
                            </div>
                        </TabPanel>
                        <!-- <TabPanel class="bg-white p-3 shadow"> -->
                        <TabPanel>
                            <TabPhotos :photos="photos" />
                        </TabPanel>
                        <!-- <TabPanel class="bg-white p-3 shadow"> -->
                        <TabPanel>
                            <template v-if="isCurrentUserAdmin">
                                <GroupForm :form="aboutForm" />
                                <PrimaryButton @click="updateGroup">
                                    Submit
                                </PrimaryButton>
                                <PrimaryButton
                                    @click="openDeleteModalAds"
                                    class="ml-4 bg-red-500 hover:bg-red-600"
                                    color="red"
                                >
                                    Delete Group
                                </PrimaryButton>
                            </template>

                            <div
                                v-else
                                class="ck-content-output dark:text-gray-100"
                                v-html="group.about"
                            ></div>
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>

            <!-- Delete Modal -->
            <TransitionRoot as="template" :show="open">
                <Dialog class="relative z-10" @close="open = false">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div
                            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                        />
                    </TransitionChild>

                    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                        <div
                            class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                        >
                            <TransitionChild
                                as="template"
                                enter="ease-out duration-300"
                                enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                enter-to="opacity-100 translate-y-0 sm:scale-100"
                                leave="ease-in duration-200"
                                leave-from="opacity-100 translate-y-0 sm:scale-100"
                                leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            >
                                <DialogPanel
                                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                                >
                                    <div
                                        class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4"
                                    >
                                        <div class="sm:flex sm:items-start">
                                            <div
                                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
                                            >
                                                <ExclamationTriangleIcon
                                                    class="h-6 w-6 text-red-600"
                                                    aria-hidden="true"
                                                />
                                            </div>
                                            <div
                                                class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left"
                                            >
                                                <DialogTitle
                                                    as="h3"
                                                    class="text-base font-semibold leading-6 text-gray-900"
                                                >
                                                    Delete Group
                                                </DialogTitle>
                                                <div class="mt-2">
                                                    <p
                                                        class="text-sm text-gray-500"
                                                    >
                                                        Are you sure you want to
                                                        delete this
                                                        Group?
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                                    >
                                        <button
                                            type="button"
                                            class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                                            @click="confirmDelete"
                                        >
                                            Delete
                                        </button>
                                        <button
                                            type="button"
                                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                                            @click="open = false"
                                            ref="cancelButtonRef"
                                        >
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
    </AuthenticatedLayout>
    <InviteUserModal v-model="showInviteUserModal" />
    <UpdateProfileReminder />
</template>

<style scoped></style>
