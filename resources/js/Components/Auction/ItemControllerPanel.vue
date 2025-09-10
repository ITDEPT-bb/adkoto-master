<template>
    <div>
        <!-- Trigger button -->
        <button
            type="button"
            @click="openModal"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
            Manage Items
        </button>

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
                                class="w-full max-w-2xl transform overflow-hidden rounded-lg bg-white p-6 text-left shadow-xl transition-all"
                            >
                                <DialogTitle
                                    as="h3"
                                    class="text-lg font-medium text-gray-900 mb-4"
                                >
                                    Manage Auction Items
                                </DialogTitle>

                                <div v-if="loading" class="text-gray-500">
                                    Loading items...
                                </div>

                                <table v-else class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th
                                                class="border px-4 py-2 text-left"
                                            >
                                                Item
                                            </th>
                                            <th
                                                class="border px-4 py-2 text-left"
                                            >
                                                Attachment
                                            </th>
                                            <th class="border px-4 py-2">
                                                Active
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="item in items"
                                            :key="item.id"
                                        >
                                            <td class="border px-4 py-2">
                                                {{ item.name }}
                                            </td>
                                            <td
                                                class="border px-4 py-2 flex items-center"
                                            >
                                                <img
                                                    :src="item.attachment_url"
                                                    alt="Item Image"
                                                    class="w-12 h-12 object-cover rounded"
                                                />
                                                <!-- <img
                                                    v-if="
                                                        item.attachments &&
                                                        item.attachments.length
                                                    "
                                                    :src="item.attachment_url"
                                                    alt="Item Image"
                                                    class="w-12 h-12 object-cover rounded"
                                                />
                                                <img
                                                    v-else
                                                    src="https://placehold.co/400"
                                                    alt="No Image"
                                                    class="w-12 h-12 object-cover rounded"
                                                /> -->
                                            </td>
                                            <td
                                                class="border px-4 py-2 text-center"
                                            >
                                                <input
                                                    type="checkbox"
                                                    :checked="item.is_active"
                                                    @change="toggleActive(item)"
                                                />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="mt-4 flex justify-between gap-4">
                                    <CreateItemModal @created="fetchItems" />

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
import CreateItemModal from "./CreateItemModal.vue";

const toast = useToast();
const isOpen = ref(false);
const items = ref([]);
const loading = ref(false);
const isLoadingBid = ref(false);

function openModal() {
    isOpen.value = true;
    fetchItems();
}

function closeModal() {
    isOpen.value = false;
}

async function fetchItems() {
    loading.value = true;
    try {
        const { data } = await axios.get("/auction/host/items");
        items.value = data;
    } catch (error) {
        console.error("Failed to fetch items", error);
    } finally {
        loading.value = false;
    }
}

async function toggleActive(item) {
    isLoadingBid.value = true;
    try {
        await axios.patch(`/auction/host/items/${item.id}/toggle-active`);
        item.is_active = !item.is_active;
        toast.success(
            item.is_active
                ? `✅ "${item.name}" is now active.`
                : `⛔ "${item.name}" has been deactivated.`
        );
    } catch (error) {
        if (error.response && error.response.data.message) {
            toast.error(error.response.data.message);
        } else {
            toast.error("Failed to update item status.");
        }
    } finally {
        isLoadingBid.value = false;
    }
}
</script>
