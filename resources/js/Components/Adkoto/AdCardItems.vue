<template>
    <div
        class="relative border dark:border-slate-700 rounded-lg mb-4 overflow-hidden shadow-lg"
    >
        <!-- <Link :href="route('adkoto.show', ad.id)" class="block w-full h-full"> -->
            <div
                v-if="ad.attachments.length > 0"
                class="w-full h-48 overflow-hidden"
            >
                <img
                    :src="`/storage/${ad.attachments[0].image_path}`"
                    alt="Ad Attachment"
                    class="w-full h-full object-contain"
                />
            </div>
            <div
                class="absolute bottom-0 left-0 right-0 top-0 bg-blue-500 bg-opacity-0 hover:bg-opacity-90 text-white p-2 opacity-0 hover:opacity-100 transition-opacity duration-300"
            >
                <h2 class="text-xl font-semibold">{{ ad.title }}</h2>
                <!-- <p class="text-white">{{ ad.description }}</p> -->
                <p class="text-white">Price: {{ ad.price }}</p>
                <p class="text-white">Location: {{ ad.location }}</p>
                <button
                    @click="editAd(ad)"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded mr-2"
                >
                    Edit
                </button>
                <!-- <button
                    @click="confirmDelete(ad.id)"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                >
                    Delete
                </button> -->
                <button
                    @click="openDeleteModalAds"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                >
                    Delete
                </button>
            </div>
        <!-- </Link> -->

        <!-- <div class="absolute top-2 right-2">
            <button @click="editAd(ad)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-2">
                Edit
            </button>
            <button @click="confirmDelete(ad.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                Delete
            </button>
        </div> -->
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

<script setup>
import { defineProps, ref } from "vue";
import { router } from "@inertiajs/vue3";
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

const openDeleteModalAds = () => {
    open.value = true;
};

const props = defineProps({
    ad: Object,
});

const confirmDelete = () => {
    router
        .delete(route("adkoto.destroy", props.ad.id), {
            preserveScroll: true,
        })
        .then(() => {
            open.value = false;
        })
        .catch((error) => {
            console.error("Failed to delete post:", error);
        });
};

const editAd = (ad) => {
    // Implement logic to open a modal for editing the ad
    console.log(`Editing ad with ID ${ad.id}`);
};

// const confirmDelete = (adId) => {
//     if (confirm(`Are you sure you want to delete ad with ID ${adId}?`)) {
//         console.log(`Deleting ad with ID ${adId}`);
//     }
// };
</script>

<style scoped>
/* No scoped styles needed here if using Tailwind classes */
</style>
