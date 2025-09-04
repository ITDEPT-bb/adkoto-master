<template>
    <div>
        <!-- Trigger button -->
        <PrimaryButton type="button" @click="openModal">
            Manage Users
        </PrimaryButton>

        <TransitionRoot appear :show="isOpen" as="template">
            <Dialog as="div" @close="closeModal" class="relative z-50">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/40" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="flex min-h-full items-center justify-center p-4 text-center"
                    >
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel
                                class="w-full max-w-3xl transform overflow-hidden rounded-lg bg-white p-6 text-left shadow-xl transition-all"
                            >
                                <DialogTitle
                                    as="h3"
                                    class="text-lg font-medium text-gray-900 mb-4"
                                >
                                    Manage Users
                                </DialogTitle>

                                <div v-if="loading" class="text-gray-500">
                                    Loading users...
                                </div>

                                <table v-else class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th
                                                class="border px-4 py-2 text-left"
                                            >
                                                Name
                                            </th>
                                            <th
                                                class="border px-4 py-2 text-left"
                                            >
                                                Email
                                            </th>
                                            <th
                                                class="border px-4 py-2 text-left"
                                            >
                                                Avatar
                                            </th>
                                            <th class="border px-4 py-2">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <Transition
                                            name="fade"
                                            v-for="user in users"
                                            :key="user.id"
                                        >
                                            <tr>
                                                <td class="border px-4 py-2">
                                                    {{ user.name }}
                                                </td>
                                                <td class="border px-4 py-2">
                                                    {{ user.email }}
                                                </td>
                                                <td
                                                    class="border px-4 py-2 flex items-center"
                                                >
                                                    <img
                                                        v-if="user.avatar_path"
                                                        :src="user.avatar_path"
                                                        alt="User Avatar"
                                                        class="w-12 h-12 object-cover rounded-full"
                                                    />
                                                    <img
                                                        v-else
                                                        src="https://placehold.co/100x100?text=User"
                                                        alt="No Avatar"
                                                        class="w-12 h-12 object-cover rounded-full"
                                                    />
                                                </td>
                                                <td
                                                    class="border px-4 py-2 text-center"
                                                >
                                                    <button
                                                        class="flex items-center justify-center gap-2 px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 disabled:opacity-50"
                                                        :disabled="
                                                            loadingIds.includes(
                                                                user.id
                                                            )
                                                        "
                                                        @click="addSeller(user)"
                                                    >
                                                        <svg
                                                            v-if="
                                                                loadingIds.includes(
                                                                    user.id
                                                                )
                                                            "
                                                            class="animate-spin h-4 w-4 text-white"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <circle
                                                                class="opacity-25"
                                                                cx="12"
                                                                cy="12"
                                                                r="10"
                                                                stroke="currentColor"
                                                                stroke-width="4"
                                                            />
                                                            <path
                                                                class="opacity-75"
                                                                fill="currentColor"
                                                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                                                            />
                                                        </svg>
                                                        <span v-else
                                                            >Add as Seller</span
                                                        >
                                                    </button>
                                                </td>
                                            </tr>
                                        </Transition>
                                    </tbody>
                                </table>

                                <div class="mt-4 flex justify-end">
                                    <button
                                        type="button"
                                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
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
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";
import { useToast } from "vue-toastification";
import PrimaryButton from "../PrimaryButton.vue";

const toast = useToast();
const isOpen = ref(false);
const users = ref([]);
const loading = ref(false);
const loadingIds = ref([]); // track per-user add state

function openModal() {
    isOpen.value = true;
    fetchUsers();
}

function closeModal() {
    isOpen.value = false;
}

async function fetchUsers() {
    loading.value = true;
    try {
        const { data } = await axios.get("/auction/host/sellers");
        users.value = data;
    } catch (error) {
        console.error("Failed to fetch users", error);
        toast.error("Failed to load users.");
    } finally {
        loading.value = false;
    }
}

async function addSeller(user) {
    loadingIds.value.push(user.id);
    try {
        await axios.post(`/auction/host/sellers`, { user_id: user.id });
        // remove from list immediately
        users.value = users.value.filter((u) => u.id !== user.id);
        toast.success(`✅ "${user.name}" has been added as a seller.`);
    } catch (error) {
        if (error.response?.data?.message) {
            toast.error(error.response.data.message);
        } else {
            toast.error("Failed to add seller.");
        }
    } finally {
        loadingIds.value = loadingIds.value.filter((id) => id !== user.id);
    }
}
</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
.fade-enter-to,
.fade-leave-from {
    opacity: 1;
    transform: translateY(0);
}
</style>
