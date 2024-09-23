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
                            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl"
                        >
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:items-start">
                                    <div
                                        class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10"
                                    >
                                        <ProfileIcon
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
                                            Add Members to Group
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <!-- Search input -->
                                            <input
                                                v-model="searchQuery"
                                                type="text"
                                                placeholder="Search for users"
                                                class="mb-4 p-2 w-full border border-gray-300 rounded-lg focus:ring focus:ring-indigo-500 focus:border-indigo-500"
                                            />

                                            <!-- User list -->
                                            <div
                                                class="max-h-60 overflow-y-auto"
                                            >
                                                <ul>
                                                    <li
                                                        v-for="user in filteredUsers"
                                                        :key="user.id"
                                                        class="p-2 hover:bg-gray-200 cursor-pointer flex justify-between"
                                                        @click="
                                                            selectUser(user)
                                                        "
                                                    >
                                                        <!-- <img
                                                            :src="
                                                                user.avatar_url
                                                            "
                                                            alt="Avatar"
                                                            class="w-10 h-10 rounded-full object-cover"
                                                        /> -->
                                                        <span
                                                            >{{ user.name }}
                                                            {{
                                                                user.surname
                                                            }}</span
                                                        >
                                                        <span
                                                            v-if="
                                                                selectedUsers.includes(
                                                                    user.id
                                                                )
                                                            "
                                                            >✔</span
                                                        >
                                                        <!-- <div
                                                            class="flex items-center space-x-4 transition-transform duration-300 ease-in-out transform hover:scale-105 hover:bg-gray-100 p-2 rounded-lg"
                                                        >
                                                            <img
                                                                :src="
                                                                    user.avatar_url
                                                                "
                                                                alt="Avatar"
                                                                class="w-10 h-10 rounded-full object-cover"
                                                            />
                                                            <div>
                                                                <h3
                                                                    class="text-lg font-medium"
                                                                >
                                                                    {{
                                                                        user.name
                                                                    }}
                                                                    {{
                                                                        user.surname
                                                                    }}
                                                                </h3>
                                                                <p
                                                                    class="text-sm text-gray-600"
                                                                >
                                                                    @{{
                                                                        user.username
                                                                    }}
                                                                </p>
                                                            </div>
                                                            <span
                                                                v-if="
                                                                    selectedUsers.includes(
                                                                        user.id
                                                                    )
                                                                "
                                                                >✔</span
                                                            >
                                                        </div> -->
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                            >
                                <button
                                    type="button"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm"
                                    @click="addSelectedUsers"
                                >
                                    Add
                                </button>
                                <button
                                    type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm"
                                    @click="closeModal"
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
</template>

<script setup>
import { ref, defineProps, defineEmits, computed, onMounted } from "vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";

import axiosClient from "@/axiosClient.js";

import ProfileIcon from "../Icons/ProfileIcon.vue";

const props = defineProps({
    isOpen: Boolean,
    kalakalitem: Object,
});

const emit = defineEmits(["close"]);
const closeModal = () => {
    emit("close");
};

const searchQuery = ref("");
const users = ref([]);
const selectedUsers = ref([]);

const fetchUsers = async () => {
    const response = await axiosClient.get("/groupchat/fetchUsers");
    users.value = response.data;
};

const filteredUsers = computed(() => {
    return users.value.filter((user) =>
        user.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const selectUser = (user) => {
    if (!selectedUsers.value.includes(user.id)) {
        selectedUsers.value.push(user.id);
    } else {
        selectedUsers.value = selectedUsers.value.filter(
            (id) => id !== user.id
        );
    }
};

const addSelectedUsers = () => {
    axiosClient
        .post("/group-chat/add-participant", {
            users: selectedUsers.value,
            group_id: props.groupId,
        })
        .then((response) => {
            console.log("Users added:", response.data);
            closeModal();
        })
        .catch((error) => {
            console.error("Error adding users:", error);
        });
};

onMounted(() => {
    fetchUsers();
});
</script>

<style scoped></style>
