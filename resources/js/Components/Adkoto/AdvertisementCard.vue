<template>
    <div
        class="w-full mx-auto p-4 bg-white dark:bg-slate-950 dark:border-none border border-gray-200 rounded-lg shadow-md"
    >
        <!-- Image Carousel -->
        <div class="relative">
            <div class="overflow-hidden rounded-t-lg">
                <div
                    class="flex"
                    :style="{
                        transform: `translateX(-${currentIndex * 100}%)`,
                        transition: 'transform 0.5s ease',
                    }"
                >
                    <img
                        v-for="(attachment, index) in advertisement.attachments"
                        :key="index"
                        :src="attachment.image_path"
                        alt="Advertisement Image"
                        class="w-full h-full object-contain"
                    />
                </div>
            </div>
            <button
                @click="prevImage"
                class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-white dark:bg-slate-950 dark:text-white text-blue-500 font-bold p-2 rounded-full"
            >
                &lt;
            </button>
            <button
                @click="nextImage"
                class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-white dark:bg-slate-950 dark:text-white text-blue-500 font-bold p-2 rounded-full"
            >
                &gt;
            </button>
        </div>

        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
                {{ advertisement.title }}
            </h1>
            <p class="text-gray-600 mb-4 dark:text-white">
                {{ advertisement.description }}
            </p>
            <div class="flex items-center gap-5">
                <span class="flex items-center text-gray-500 dark:text-white">
                    <UserIcon class="w-5 h-5 mr-2 dark:text-white" />
                    {{ advertisement.user.name }}
                </span>
                <span class="flex items-center text-gray-500 dark:text-white">
                    <ClockIcon class="w-5 h-5 mr-2 dark:text-white" />
                    {{ dayjs(advertisement.updated_at).fromNow() }}
                </span>
                <span class="flex items-center text-gray-500 dark:text-white">
                    <SettingsIcon class="w-5 h-5 mr-2" />
                    {{ advertisement.category.name }}
                </span>
                <span
                    class="bg-blue-500 flex items-center text-white py-1 px-2 rounded text-xs"
                >
                    <TagIcon class="w-4 h-4 mr-1 dark:text-white" />
                    {{ advertisement.price }}
                </span>
            </div>
        </div>

        <!-- Manage Button (Edit/Delete) -->
        <div v-if="advertisement.user.id === authUser.id" class="flex-2">
            <Link
                :href="
                    route('adkoto.edit', {
                        id: advertisement.id,
                    })
                "
                class="bg-yellow-500 text-white py-1 px-2 rounded text-xs mr-2"
            >
                Edit
            </Link>
            <button
                @click="openDeleteModal"
                class="bg-red-500 text-white py-1 px-2 rounded text-xs"
            >
                Delete
            </button>
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
                        class="fixed inset-0 bg-gray-600 bg-opacity-90 transition-opacity"
                    />
                </TransitionChild>

                <div
                    class="fixed inset-0 z-10 w-screen overflow-y-auto scrollbar-thin"
                >
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
                                                Delete Advertisement
                                            </DialogTitle>
                                            <div class="mt-2">
                                                <p
                                                    class="text-sm text-gray-500"
                                                >
                                                    Are you sure you want to
                                                    delete this Advertisement?
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
                                        :disabled="isLoading"
                                        :class="{
                                            'inline-flex w-full justify-center rounded-md bg-red-200 px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto cursor-not-allowed':
                                                isLoading,
                                            'inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto':
                                                !isLoading,
                                        }"
                                        @click="deleteAd(advertisement.id)"
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

<script setup>
import { ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import UserIcon from "@/Components/Icons/UserIcon.vue";
import ClockIcon from "@/Components/Icons/ClockIcon.vue";
import SettingsIcon from "@/Components/Icons/SettingsIcon.vue";
import TagIcon from "../Icons/TagIcon.vue";
import axiosClient from "@/axiosClient.js";
import { useToast } from "vue-toastification";

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

dayjs.extend(relativeTime);

const { props } = usePage();
const advertisement = ref(props.advertisement);
const emit = defineEmits(["deleted"]);

const toast = useToast();

// Get the authenticated user
const authUser = usePage().props.auth.user;

const currentIndex = ref(0);

const nextImage = () => {
    if (currentIndex.value < advertisement.value.attachments.length - 1) {
        currentIndex.value++;
    } else {
        currentIndex.value = 0;
    }
};

const prevImage = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    } else {
        currentIndex.value = advertisement.value.attachments.length - 1;
    }
};

const isLoading = ref(false);
const deleteAd = async (id) => {
    isLoading.value = true;
    try {
        await axios.delete(`/adkoto/${id}`);
        toast.success("Advertisement deleted successfully!");
        emit("deleted", id);
        isLoading.value = false;
        window.location.href = "/adkoto";
    } catch (error) {
        toast.error("Failed to delete the advertisement.");
        console.error(error);
        isLoading.value = false;
    }
};
</script>

<style scoped>
/* Additional styles if needed */
</style>
