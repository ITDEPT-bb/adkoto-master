<template>
    <Dialog
        :open="isOpenAvatar"
        @close="closeModalAvatar"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"
    >
        <DialogPanel
            class="relative w-full max-w-lg h-auto mx-auto p-4 bg-white rounded-lg flex flex-col items-center"
        >
            <!-- Avatar Image -->
            <img
                :src="imageSrc || '/img/default_avatar.webp'"
                class="w-24 h-24 sm:w-48 sm:h-48 object-cover"
                alt="Avatar Image"
            />

            <!-- Ellipsis Button for Actions -->
            <div class="absolute top-4 right-4">
                <Link
                    :href="
                        route('profile.avatarPage', {
                            user: authUser.id,
                        })
                    "
                >
                    <button
                        class="text-white p-2 bg-gray-800 rounded-full hover:bg-gray-700"
                    >
                        <!-- <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z"
                            />
                        </svg> -->
                        Update Avatar
                    </button>
                </Link>
            </div>

            <!-- Close Button -->
            <button
                @click="closeModalAvatar"
                class="absolute top-4 left-4 text-dark"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </DialogPanel>
    </Dialog>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import { Dialog, DialogPanel } from "@headlessui/vue";

const authUser = usePage().props.auth.user;

const props = defineProps({
    imageSrc: {
        type: String,
        required: false,
    },
    isOpenAvatar: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(["close"]);

const closeModalAvatar = () => {
    emit("close");
};
</script>
