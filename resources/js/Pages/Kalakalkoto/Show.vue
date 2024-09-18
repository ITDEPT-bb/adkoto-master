<template>
    <Head title="Item" />

    <KalakalLayout>
        <div class="max-w-7xl mx-auto h-full overflow-y-auto p-4">
            <KalakalMenu />
            <div class="px-4 mx-auto 2xl:px-0 my-4">
                <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
                    <!-- Image Carousel -->
                    <div class="relative">
                        <div class="overflow-hidden rounded-t-lg">
                            <div
                                class="flex"
                                :style="{
                                    transform: `translateX(-${
                                        currentIndex * 100
                                    }%)`,
                                    transition: 'transform 0.5s ease',
                                }"
                            >
                                <img
                                    v-for="(
                                        attachment, index
                                    ) in kalakalitem.attachments"
                                    :key="index"
                                    :src="attachment.image_path"
                                    alt="Kalakal Image"
                                    class="w-full h-full object-contain"
                                />
                            </div>
                        </div>
                        <button
                            @click="prevImage"
                            class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-white text-blue-500 font-bold p-2 rounded-full"
                        >
                            &lt;
                        </button>
                        <button
                            @click="nextImage"
                            class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-white text-blue-500 font-bold p-2 rounded-full"
                        >
                            &gt;
                        </button>
                    </div>

                    <!-- Item Details -->
                    <div class="mt-6 sm:mt-8 lg:mt-0">
                        <h1
                            class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white"
                        >
                            {{ kalakalitem.name }}
                        </h1>
                        <div class="mt-4 sm:items-center sm:gap-4 sm:flex">
                            <p
                                class="text-2xl font-extrabold text-gray-900 sm:text-3xl dark:text-white"
                            >
                                {{ formatPrice(kalakalitem.price) }}
                            </p>
                        </div>

                        <div
                            v-if="kalakalitem.user.id !== authUser.id"
                            class="mt-6 sm:gap-4 sm:items-center sm:flex sm:mt-8"
                        >
                            <!-- <a
                                href="#"
                                title=""
                                class="flex items-center justify-center py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                role="button"
                            > -->
                            <Link
                                :href="
                                    route('chat.conversations.show', {
                                        user: kalakalitem.user.id,
                                    })
                                "
                            >
                                <div class="flex gap-3 hover:underline">
                                    <MessageIcon />
                                    <p class="font-bold">Contact Seller</p>
                                </div>
                                <!-- </a> -->
                            </Link>
                        </div>

                        <hr
                            class="my-6 md:my-8 border-gray-200 dark:border-gray-800"
                        />

                        <div
                            class="flex items-center text-gray-700 my-2 dark:text-gray-300"
                        >
                            <UserIcon
                                class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400"
                            />
                            <p
                                class="text-sm font-semibold text-gray-600 dark:text-gray-400"
                            >
                                {{ kalakalitem.user.name }}
                                {{ kalakalitem.user.surname }}
                            </p>
                        </div>

                        <div
                            class="flex items-center text-gray-700 my-2 dark:text-gray-300"
                        >
                            <MapPinIcon
                                class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400"
                            />
                            <p
                                class="text-sm font-semibold text-gray-600 dark:text-gray-400"
                            >
                                {{ kalakalitem.location }}
                            </p>
                        </div>

                        <p class="mb-6 text-gray-500 dark:text-gray-400">
                            {{ kalakalitem.description }}
                        </p>
                    </div>
                </div>

                <!-- Manage Button (Edit/Delete) -->
                <div v-if="kalakalitem.user.id === authUser.id" class="flex-2">
                    <a
                        :href="
                            route('kalakalkoto.edit', {
                                id: kalakalitem.id,
                            })
                        "
                        class="bg-yellow-500 text-white py-1 px-2 rounded text-xs mr-2 hover:bg-yellow-800"
                    >
                        Edit
                    </a>
                    <button
                        @click="openDeleteModal"
                        class="bg-red-500 text-white py-1 px-2 rounded text-xs m-2 hover:bg-red-800"
                    >
                        Delete
                    </button>
                    <button
                        @click="openMarkAsSoldModal"
                        class="bg-blue-500 text-white py-1 px-2 rounded text-xs m-2 hover:bg-blue-800"
                    >
                        Mark As Sold
                    </button>
                    <button
                        @click="auctionModalOpen = true"
                        class="bg-green-500 text-white py-1 px-2 rounded text-xs m-2 hover:bg-green-800"
                    >
                        Move to Auction
                    </button>
                    <MoveToAuctionModal
                        :isOpen="auctionModalOpen"
                        :kalakalitem="kalakalitem"
                        @close="auctionModalOpen = false"
                    />
                </div>
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
                                                    Delete Listing
                                                </DialogTitle>
                                                <div class="mt-2">
                                                    <p
                                                        class="text-sm text-gray-500"
                                                    >
                                                        Are you sure you want to
                                                        delete this listing?
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
                                            @click="deleteAd(kalakalitem.id)"
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

            <!-- Mark as Sold Modal -->
            <TransitionRoot as="template" :show="markOpen">
                <Dialog class="relative z-10" @close="markOpen = false">
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
                                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10"
                                            >
                                                <CheckCircleIcon
                                                    class="h-6 w-6 text-blue-600"
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
                                                    Mark as Sold
                                                </DialogTitle>
                                                <div class="mt-2">
                                                    <p
                                                        class="text-sm text-gray-500"
                                                    >
                                                        Are you sure you want to
                                                        mark it as sold?
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
                                            :disabled="isLoadingSold"
                                            :class="{
                                                'inline-flex w-full justify-center rounded-md bg-blue-200 px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto cursor-not-allowed':
                                                    isLoadingSold,
                                                'inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-900 sm:ml-3 sm:w-auto':
                                                    !isLoadingSold,
                                            }"
                                            @click="markAsSold(kalakalitem.id)"
                                        >
                                            Mark as Sold
                                        </button>
                                        <button
                                            type="button"
                                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                                            @click="markOpen = false"
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
    </KalakalLayout>

    <UpdateProfileReminder />
</template>

<script setup>
import { ref } from "vue";
import { usePage, Head, Link } from "@inertiajs/vue3";
import KalakalLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import KalakalMenu from "@/Components/Kalakalkoto/KalakalMenu.vue";
import axiosClient from "@/axiosClient.js";
import { useToast } from "vue-toastification";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import {
    ExclamationTriangleIcon,
    CheckCircleIcon,
} from "@heroicons/vue/24/outline";
import MessageIcon from "@/Components/Icons/MessageIcon.vue";
import UserIcon from "@/Components/Icons/UserIcon.vue";
import MapPinIcon from "@/Components/Icons/MapPinIcon.vue";

import MoveToAuctionModal from "@/Components/Kalakalkoto/MoveToAuctionModal.vue";
const auctionModalOpen = ref(false);

const open = ref(false);
const openDeleteModal = () => {
    open.value = true;
};

const markOpen = ref(false);
const openMarkAsSoldModal = () => {
    markOpen.value = true;
};

const toast = useToast();
const { props } = usePage();
const kalakalitem = ref(props.kalakalitem);
const emit = defineEmits(["deleted", "sold"]);

const authUser = usePage().props.auth.user;

const formatPrice = (price) => {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
        minimumFractionDigits: 2,
    }).format(price);
};

const currentIndex = ref(0);
const nextImage = () => {
    if (kalakalitem.value.attachments.length > 0) {
        if (currentIndex.value < kalakalitem.value.attachments.length - 1) {
            currentIndex.value++;
        } else {
            currentIndex.value = 0;
        }
    }
};

const prevImage = () => {
    if (kalakalitem.value.attachments.length > 0) {
        if (currentIndex.value > 0) {
            currentIndex.value--;
        } else {
            currentIndex.value = kalakalitem.value.attachments.length - 1;
        }
    }
};

const isLoading = ref(false);
const deleteAd = async (id) => {
    isLoading.value = true;
    try {
        await axios.delete(`/kalakalkoto/${id}`);
        toast.success("Listing deleted successfully!");
        emit("deleted", id);
        isLoading.value = false;
        window.location.href = "/kalakalkoto";
    } catch (error) {
        toast.error("Failed to delete the Listing.");
        console.error(error);
        isLoading.value = false;
    }
};

const isLoadingSold = ref(false);
const markAsSold = async (id) => {
    isLoadingSold.value = true;
    try {
        await axios.put(`/kalakalkoto/mark-as-sold/${id}`);
        toast.success("Item marked as sold successfully!");
        emit("sold", id);
        isLoadingSold.value = false;
        window.location.href = "/kalakalkoto";
    } catch (error) {
        toast.error("Failed to mark the item as sold.");
        console.error(error);
    } finally {
        isLoadingSold.value = false;
    }
};
</script>

<style scoped>
/* Add your custom styles here */
</style>
