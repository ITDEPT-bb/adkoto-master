<template>
    <TransitionRoot as="template" :show="isOpen">
        <Dialog class="relative z-10" @close="closeModal">
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
                            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl"
                        >
                            <div
                                class="bg-white dark:bg-slate-950 px-4 pb-4 pt-5 sm:p-6 sm:pb-4"
                            >
                                <div class="sm:items-start">
                                    <div
                                        class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10"
                                    >
                                        <ProfileIcon
                                            class="h-6 w-6 text-blue-600"
                                            aria-hidden="true"
                                        />
                                    </div>
                                    <button
                                        @click="addMemberModal = true"
                                        class="py-2.5 px-5 w-full my-6 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    >
                                        Add Member
                                    </button>
                                    <AddMemberModal
                                        :isOpen="addMemberModal"
                                        :groupId="group.id"
                                        @close="addMemberModal = false"
                                        @closeBoth="handleCloseBoth"
                                    />
                                    <div
                                        class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left"
                                    >
                                        <DialogTitle
                                            as="h3"
                                            class="text-base mt-3 font-semibold leading-6 text-gray-900 dark:text-white"
                                        >
                                            "{{ group.name }}" Members
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <!-- User list -->
                                            <div
                                                class="max-h-60 overflow-y-auto scrollbar-thin"
                                            >
                                                <ul
                                                    v-for="member in group.participants"
                                                    :key="member.id"
                                                    class="space-y-2 font-medium"
                                                >
                                                    <li
                                                        class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                                                    >
                                                        <Link
                                                            :href="
                                                                route(
                                                                    'profile',
                                                                    member.username
                                                                )
                                                            "
                                                            class="flex items-center text-gray-900 dark:text-white group"
                                                        >
                                                            <img
                                                                :src="
                                                                    member.avatar_url
                                                                "
                                                                alt="Avatar"
                                                                class="w-8 h-8 rounded-full object-cover"
                                                            />
                                                            <span
                                                                class="ml-4 text-gray-900 dark:text-white group-hover:text-gray-900 dark:group-hover:text-white"
                                                            >
                                                                {{
                                                                    member.name
                                                                }}
                                                                {{
                                                                    member.surname
                                                                }}
                                                            </span>
                                                        </Link>
                                                        <button
                                                            v-if="
                                                                member.id !==
                                                                authUser.id
                                                            "
                                                            @click="
                                                                removeUser(
                                                                    member.id
                                                                )
                                                            "
                                                            class="text-red-500 hover:text-red-700 ml-4"
                                                        >
                                                            Remove
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-gray-50 dark:bg-slate-950 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                            >
                                <button
                                    type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm"
                                    @click="closeModal"
                                >
                                    Close
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { ref, defineProps, defineEmits, computed, onMounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";

import ProfileIcon from "../Icons/ProfileIcon.vue";
import AddMemberModal from "@/Components/GroupChat/AddMemberModal.vue";

const props = defineProps({
    isOpen: Boolean,
    group: Object,
});

const authUser = usePage().props.auth.user;

const emit = defineEmits(["close"]);
const closeModal = () => {
    emit("close");
};

const addMemberModal = ref(false);

const handleCloseBoth = () => {
    addMemberModal.value = false;
    closeModal();
};

const removeUser = async (userId) => {
    try {
        const response = await axios.post(
            `/group-chat/${props.group.id}/remove-user`,
            { userId }
        );

        alert(response.data.message);

        if (response.data.redirect) {
            window.location.href = response.data.redirect;
        }
    } catch (error) {
        console.error(error);
        alert(error.response?.data?.message || "Failed to remove user");
    }
};
</script>

<style scoped></style>
