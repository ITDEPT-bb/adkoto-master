<script setup>
import {
    ChatBubbleLeftRightIcon,
    HandThumbUpIcon,
} from "@heroicons/vue/24/outline";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import PostUserHeader from "@/Components/Tribekoto/PostUserHeader.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import axiosClient from "@/axiosClient.js";
import ReadMoreReadLess from "@/Components/Tribekoto/ReadMoreReadLess.vue";
import EditDeleteDropdown from "@/Components/Tribekoto/EditDeleteDropdown.vue";
import PostAttachments from "@/Components/Tribekoto/PostAttachments.vue";
import CommentList from "@/Components/Tribekoto/CommentList.vue";
import { computed, ref } from "vue";
import UrlPreview from "@/Components/Tribekoto/UrlPreview.vue";
import { MapPinIcon } from "@heroicons/vue/24/outline/index.js";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";

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
            return `${
                group1 || ""
            }<a href="/search/${encodedGroup}" class="hashtag">${group2}</a>`;
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

function sendReaction() {
    axiosClient
        .post(route("post.reaction", props.post), {
            reaction: "like",
        })
        .then(({ data }) => {
            props.post.current_user_has_reaction =
                data.current_user_has_reaction;
            props.post.num_of_reactions = data.num_of_reactions;
        });
}
</script>

<template>
    <div
        class="bg-white border dark:bg-slate-950 dark:border-slate-900 dark:text-gray-100 rounded p-4 mb-3"
    >
        <div class="flex items-center justify-between mb-3">
            <PostUserHeader :post="post" />
            <div class="flex items-center gap-2">
                <div v-if="isPinned" class="flex items-center text-xs">
                    <MapPinIcon class="h-3 w-3" aria-hidden="true" />
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
                    @pin="pinUnpinPost"
                />
            </div>
        </div>
        <div class="mb-3">
            <ReadMoreReadLess :content="postBody" />
            <UrlPreview :preview="post.preview" :url="post.preview_url" />
        </div>
        <div
            class="grid gap-3 mb-3"
            :class="[
                post.attachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2',
            ]"
        >
            <PostAttachments
                :attachments="post.attachments"
                @attachmentClick="openAttachment"
            />
        </div>
        <Disclosure v-slot="{ open }">
            <!--            Like & Comment buttons-->
            <div class="flex gap-2">
                <button
                    @click="sendReaction"
                    class="text-gray-800 dark:text-gray-100 flex gap-1 items-center justify-center rounded-lg py-2 px-4 flex-1"
                    :class="[
                        post.current_user_has_reaction
                            ? 'bg-red-300 dark:bg-sky-900 hover:bg-red-400 dark:hover:bg-sky-950'
                            : 'bg-red-100 dark:bg-slate-900 hover:bg-red-200 dark:hover:bg-slate-800',
                    ]"
                >
                    <HandThumbUpIcon class="w-5 h-5" />
                    <span class="mr-2">{{ post.num_of_reactions }}</span>
                    {{ post.current_user_has_reaction ? "Unlike" : "Like" }}
                </button>
                <DisclosureButton
                    class="text-gray-800 dark:text-gray-100 flex gap-1 items-center justify-center bg-gray-100 dark:bg-slate-900 dark:hover:bg-slate-800 rounded-lg hover:bg-gray-200 py-2 px-4 flex-1"
                >
                    <ChatBubbleLeftRightIcon class="w-5 h-5" />
                    <span class="mr-2">{{ post.num_of_comments }}</span>
                    Comment
                </DisclosureButton>
            </div>

            <DisclosurePanel
                class="comment-list mt-3 max-h-[400px] overflow-auto"
            >
                <CommentList :post="post" :data="{ comments: post.comments }" />
            </DisclosurePanel>
        </Disclosure>

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
                                                Delete Post
                                            </DialogTitle>
                                            <div class="mt-2">
                                                <p
                                                    class="text-sm text-gray-500"
                                                >
                                                    Are you sure you want to
                                                    delete this post?
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
</template>
