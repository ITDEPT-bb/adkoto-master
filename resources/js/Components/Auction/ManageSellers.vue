<!-- <template>
    <div>
        <PrimaryButton type="button" @click="openModal">
            Manage Sellers
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
                                class="w-full max-w-3xl rounded-lg bg-white p-6 text-left shadow-xl transition-all"
                            >
                                <DialogTitle
                                    class="text-lg font-medium text-gray-900 mb-4"
                                >
                                    Manage Auction Sellers
                                </DialogTitle>

                                <div v-if="loading" class="text-gray-500">
                                    Loading sellers...
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
                                                Status
                                            </th>
                                            <th class="border px-4 py-2">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="seller in sellers"
                                            :key="seller.id"
                                        >
                                            <td class="border px-4 py-2">
                                                {{ seller.user.name }}
                                            </td>
                                            <td class="border px-4 py-2">
                                                {{ seller.user.email }}
                                            </td>
                                            <td
                                                class="border px-4 py-2 flex items-center"
                                            >
                                                <img
                                                    v-if="
                                                        seller.user.avatar_path
                                                    "
                                                    :src="
                                                        seller.user.avatar_path
                                                    "
                                                    class="w-12 h-12 object-cover rounded-full"
                                                />
                                                <img
                                                    v-else
                                                    src="https://placehold.co/100x100?text=User"
                                                    class="w-12 h-12 object-cover rounded-full"
                                                />
                                            </td>
                                            <td
                                                class="border px-4 py-2 text-center"
                                            >
                                                <span
                                                    class="px-2 py-1 rounded text-xs"
                                                    :class="
                                                        seller.is_active
                                                            ? 'bg-green-100 text-green-800'
                                                            : 'bg-gray-100 text-gray-600'
                                                    "
                                                >
                                                    {{
                                                        seller.is_active
                                                            ? "Active"
                                                            : "Inactive"
                                                    }}
                                                </span>
                                            </td>
                                            <td
                                                class="border px-4 py-2 text-center"
                                            >
                                                <button
                                                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                                                    :disabled="
                                                        loadingIds.includes(
                                                            seller.id
                                                        )
                                                    "
                                                    @click="
                                                        toggleSeller(seller)
                                                    "
                                                >
                                                    {{
                                                        seller.is_active
                                                            ? "Deactivate"
                                                            : "Activate"
                                                    }}
                                                </button>
                                            </td>
                                        </tr>
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
const sellers = ref([]);
const loading = ref(false);
const loadingIds = ref([]);

function openModal() {
    isOpen.value = true;
    fetchSellers();
}

function closeModal() {
    isOpen.value = false;
}

async function fetchSellers() {
    loading.value = true;
    try {
        const { data } = await axios.get("/auction/host/active-sellers");
        sellers.value = data;
    } catch (error) {
        console.error(error);
        toast.error("Failed to load sellers.");
    } finally {
        loading.value = false;
    }
}

async function toggleSeller(seller) {
    loadingIds.value.push(seller.id);
    try {
        const { data } = await axios.patch(
            `/auction/host/sellers/${seller.id}/toggle-active`
        );
        await fetchSellers();
        toast.success(data.message);
    } catch (error) {
        console.error(error);
        toast.error("Failed to update seller status.");
    } finally {
        loadingIds.value = loadingIds.value.filter((id) => id !== seller.id);
    }
}

async function removeSeller(seller) {
    loadingIds.value.push(seller.id);
    try {
        const { data } = await axios.delete(
            `/auction/host/sellers/${seller.id}/delete`
        );
        await fetchSellers();
        toast.success(data.message);
    } catch (error) {
        console.error(error);
        toast.error("Failed to update seller status.");
    } finally {
        loadingIds.value = loadingIds.value.filter((id) => id !== seller.id);
    }
}
</script> -->

<template>
    <div>
        <!-- Trigger button -->
        <PrimaryButton type="button" @click="openModal">
            Manage Auction
        </PrimaryButton>

        <TransitionRoot appear :show="isOpen" as="template">
            <Dialog as="div" @close="closeModal" class="relative z-50">
                <!-- Overlay -->
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

                <!-- Modal content -->
                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="flex min-h-full items-center justify-center p-4 sm:p-6"
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
                                class="w-full max-w-6xl transform overflow-hidden rounded-xl bg-white shadow-xl transition-all"
                            >
                                <!-- Header -->
                                <div
                                    class="flex items-center justify-between border-b px-6 py-4"
                                >
                                    <DialogTitle
                                        class="text-xl font-semibold text-gray-900"
                                    >
                                        Manage Auction
                                    </DialogTitle>
                                    <button
                                        type="button"
                                        class="text-gray-400 hover:text-gray-600"
                                        @click="closeModal"
                                    >
                                        <span class="sr-only">Close</span>
                                        ✕
                                    </button>
                                </div>

                                <!-- Tabs -->
                                <div class="flex border-b px-6">
                                    <button
                                        class="px-4 py-3 text-sm font-medium focus:outline-none"
                                        :class="
                                            activeTab === 'users'
                                                ? 'border-b-2 border-blue-600 text-blue-600'
                                                : 'text-gray-500 hover:text-gray-700'
                                        "
                                        @click="activeTab = 'users'"
                                    >
                                        Users
                                    </button>
                                    <button
                                        class="px-4 py-3 text-sm font-medium focus:outline-none"
                                        :class="
                                            activeTab === 'sellers'
                                                ? 'border-b-2 border-blue-600 text-blue-600'
                                                : 'text-gray-500 hover:text-gray-700'
                                        "
                                        @click="activeTab = 'sellers'"
                                    >
                                        Sellers
                                    </button>
                                </div>

                                <!-- Tab Content -->
                                <div class="px-6 py-4">
                                    <!-- Users Tab -->
                                    <div v-if="activeTab === 'users'">
                                        <!-- Search -->
                                        <div class="mb-4">
                                            <input
                                                v-model="userSearch"
                                                type="text"
                                                placeholder="Search users..."
                                                class="w-full max-w-sm border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                            />
                                        </div>

                                        <!-- Loading -->
                                        <div
                                            v-if="loadingUsers"
                                            class="text-gray-500 py-4 text-center"
                                        >
                                            Loading users...
                                        </div>

                                        <!-- Table -->
                                        <div v-else class="overflow-x-auto">
                                            <table
                                                class="w-full text-sm border"
                                            >
                                                <thead class="bg-gray-50">
                                                    <tr>
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
                                                        <th
                                                            class="border px-4 py-2 text-center"
                                                        >
                                                            Actions
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        v-for="user in users"
                                                        :key="user.id"
                                                        class="hover:bg-gray-50"
                                                    >
                                                        <td
                                                            class="border px-4 py-2"
                                                        >
                                                            {{ user.name }}
                                                            {{ user.surname }}
                                                        </td>
                                                        <td
                                                            class="border px-4 py-2"
                                                        >
                                                            {{ user.email }}
                                                        </td>
                                                        <td
                                                            class="border px-4 py-2"
                                                        >
                                                            <img
                                                                v-if="
                                                                    user.avatar_url
                                                                "
                                                                :src="
                                                                    user.avatar_url
                                                                "
                                                                class="w-10 h-10 object-cover rounded-full mx-auto"
                                                            />
                                                            <img
                                                                v-else
                                                                src="https://placehold.co/100x100?text=User"
                                                                class="w-10 h-10 object-cover rounded-full mx-auto"
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
                                                                @click="
                                                                    addSeller(
                                                                        user
                                                                    )
                                                                "
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
                                                                    >Add as
                                                                    Seller</span
                                                                >
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Pagination -->
                                        <div
                                            class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-2 text-sm"
                                        >
                                            <button
                                                :disabled="
                                                    userPagination.current_page ===
                                                    1
                                                "
                                                @click="
                                                    fetchUsers(
                                                        userPagination.current_page -
                                                            1
                                                    )
                                                "
                                                class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50"
                                            >
                                                Prev
                                            </button>
                                            <span>
                                                Page
                                                {{
                                                    userPagination.current_page
                                                }}
                                                of
                                                {{ userPagination.last_page }}
                                            </span>
                                            <button
                                                :disabled="
                                                    userPagination.current_page ===
                                                    userPagination.last_page
                                                "
                                                @click="
                                                    fetchUsers(
                                                        userPagination.current_page +
                                                            1
                                                    )
                                                "
                                                class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50"
                                            >
                                                Next
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Sellers Tab -->
                                    <div v-if="activeTab === 'sellers'">
                                        <!-- Search -->
                                        <div class="mb-4">
                                            <input
                                                v-model="sellerSearch"
                                                type="text"
                                                placeholder="Search sellers..."
                                                class="w-full max-w-sm border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                            />
                                        </div>

                                        <!-- Loading -->
                                        <div
                                            v-if="loadingSellers"
                                            class="text-gray-500 py-4 text-center"
                                        >
                                            Loading sellers...
                                        </div>

                                        <!-- Table -->
                                        <div v-else class="overflow-x-auto">
                                            <table
                                                class="w-full text-sm border"
                                            >
                                                <thead class="bg-gray-50">
                                                    <tr>
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
                                                        <th
                                                            class="border px-4 py-2 text-center"
                                                        >
                                                            Status
                                                        </th>
                                                        <th
                                                            class="border px-4 py-2 text-center"
                                                        >
                                                            Actions
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        v-for="seller in sellers"
                                                        :key="seller.id"
                                                        class="hover:bg-gray-50"
                                                    >
                                                        <td
                                                            class="border px-4 py-2"
                                                        >
                                                            {{
                                                                seller.user.name
                                                            }}
                                                            {{
                                                                seller.user
                                                                    .surname
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border px-4 py-2"
                                                        >
                                                            {{
                                                                seller.user
                                                                    .email
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border px-4 py-2"
                                                        >
                                                            <img
                                                                v-if="
                                                                    seller.user
                                                                        .avatar_path
                                                                "
                                                                :src="
                                                                    seller.user
                                                                        .avatar_path
                                                                "
                                                                class="w-10 h-10 object-cover rounded-full mx-auto"
                                                            />
                                                            <img
                                                                v-else
                                                                src="https://placehold.co/100x100?text=User"
                                                                class="w-10 h-10 object-cover rounded-full mx-auto"
                                                            />
                                                        </td>
                                                        <td
                                                            class="border px-4 py-2 text-center"
                                                        >
                                                            <span
                                                                class="px-2 py-1 rounded text-xs font-medium"
                                                                :class="
                                                                    seller.is_active
                                                                        ? 'bg-green-100 text-green-800'
                                                                        : 'bg-gray-100 text-gray-600'
                                                                "
                                                            >
                                                                {{
                                                                    seller.is_active
                                                                        ? "Active"
                                                                        : "Inactive"
                                                                }}
                                                            </span>
                                                        </td>
                                                        <td
                                                            class="border px-4 py-2 text-center space-x-2"
                                                        >
                                                            <button
                                                                class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                                                                :disabled="
                                                                    loadingIds.includes(
                                                                        seller.id
                                                                    )
                                                                "
                                                                @click="
                                                                    toggleSeller(
                                                                        seller
                                                                    )
                                                                "
                                                            >
                                                                {{
                                                                    seller.is_active
                                                                        ? "Deactivate"
                                                                        : "Activate"
                                                                }}
                                                            </button>
                                                            <button
                                                                class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 disabled:opacity-50"
                                                                :disabled="
                                                                    loadingIds.includes(
                                                                        seller.id
                                                                    )
                                                                "
                                                                @click="
                                                                    removeSeller(
                                                                        seller
                                                                    )
                                                                "
                                                            >
                                                                Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Pagination -->
                                        <div
                                            class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-2 text-sm"
                                        >
                                            <button
                                                :disabled="
                                                    sellerPagination.current_page ===
                                                    1
                                                "
                                                @click="
                                                    fetchSellers(
                                                        sellerPagination.current_page -
                                                            1
                                                    )
                                                "
                                                class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50"
                                            >
                                                Prev
                                            </button>
                                            <span>
                                                Page
                                                {{
                                                    sellerPagination.current_page
                                                }}
                                                of
                                                {{ sellerPagination.last_page }}
                                            </span>
                                            <button
                                                :disabled="
                                                    sellerPagination.current_page ===
                                                    sellerPagination.last_page
                                                "
                                                @click="
                                                    fetchSellers(
                                                        sellerPagination.current_page +
                                                            1
                                                    )
                                                "
                                                class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50"
                                            >
                                                Next
                                            </button>
                                        </div>
                                    </div>
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
import { ref, watch } from "vue";
import axios from "axios";
import { useToast } from "vue-toastification";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";
import PrimaryButton from "../PrimaryButton.vue";

const toast = useToast();

const isOpen = ref(false);
const activeTab = ref("users");

const users = ref([]);
const sellers = ref([]);
const loadingIds = ref([]);

const userSearch = ref("");
const sellerSearch = ref("");

const loadingUsers = ref(false);

const userPagination = ref({ current_page: 1, last_page: 1 });
const sellerPagination = ref({ current_page: 1, last_page: 1 });

let searchTimeout;

// helpers
function debounceFetch(fn, delay = 400) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(fn, delay);
}

function openModal() {
    isOpen.value = true;
    fetchUsers();
    fetchSellers();
}
function closeModal() {
    isOpen.value = false;
}

async function fetchUsers(page = 1) {
    loadingUsers.value = true;
    try {
        const { data } = await axios.get("/auction/host/sellers", {
            params: { search: userSearch.value, page },
        });
        users.value = data.data;
        userPagination.value = {
            current_page: data.current_page,
            last_page: data.last_page,
        };
    } catch {
        toast.error("Failed to load users.");
    } finally {
        loadingUsers.value = false;
    }
}

async function fetchSellers(page = 1) {
    loadingUsers.value = true;
    try {
        const { data } = await axios.get("/auction/host/active-sellers", {
            params: { search: sellerSearch.value, page },
        });
        sellers.value = data.data;
        sellerPagination.value = {
            current_page: data.current_page,
            last_page: data.last_page,
        };
    } catch {
        toast.error("Failed to load sellers.");
    } finally {
        loadingUsers.value = false;
    }
}

async function addSeller(user) {
    loadingIds.value.push(user.id);
    try {
        await axios.post(`/auction/host/sellers`, { user_id: user.id });
        toast.success(`"${user.name}" added as seller.`);
        fetchUsers();
        fetchSellers();
    } catch (e) {
        toast.error(e.response?.data?.message || "Failed to add seller.");
    } finally {
        loadingIds.value = loadingIds.value.filter((id) => id !== user.id);
    }
}

async function toggleSeller(seller) {
    loadingIds.value.push(seller.id);
    try {
        const { data } = await axios.patch(
            `/auction/host/sellers/${seller.id}/toggle-active`
        );
        fetchSellers();
        toast.success(data.message);
    } catch {
        toast.error("Failed to update seller status.");
    } finally {
        loadingIds.value = loadingIds.value.filter((id) => id !== seller.id);
    }
}

async function removeSeller(seller) {
    if (!confirm(`Remove "${seller.user.name}" as seller?`)) return;
    loadingIds.value.push(seller.id);
    try {
        const { data } = await axios.delete(
            `/auction/host/sellers/${seller.id}/delete`
        );
        fetchSellers();
        fetchUsers();
        toast.success(data.message);
    } catch {
        toast.error("Failed to delete seller.");
    } finally {
        loadingIds.value = loadingIds.value.filter((id) => id !== seller.id);
    }
}

// watch searches with debounce
watch(userSearch, () => debounceFetch(() => fetchUsers(1)));
watch(sellerSearch, () => debounceFetch(() => fetchSellers(1)));
</script>
