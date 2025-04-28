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
                            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                        >
                            <div
                                class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 dark:bg-slate-950"
                            >
                                <div class="sm:flex sm:items-start">
                                    <div
                                        class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10"
                                    >
                                        <ArrowTopRightOnSquareIcon
                                            class="h-6 w-6 text-blue-600"
                                            aria-hidden="true"
                                        />
                                    </div>
                                    <div
                                        class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left"
                                    >
                                        <DialogTitle
                                            as="h3"
                                            class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
                                            >Recharge Your Wallet</DialogTitle
                                        >
                                        <p
                                            class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            Choose an amount to add to your
                                            wallet balance. Your wallet will be
                                            recharged upon confirmation.
                                        </p>
                                        <div class="mt-4 space-y-4">
                                            <!-- <div class="flex items-center">
												<input
													id="amount-50"
													type="radio"
													v-model="rechargeAmount"
													value="50"
													class="form-radio w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" />
												<label
													for="amount-50"
													class="ml-2 text-sm font-semibold text-gray-900"
													>₱ 50</label
												>
											</div> -->
                                            <div class="flex items-center">
                                                <input
                                                    id="amount-100"
                                                    type="radio"
                                                    v-model="rechargeAmount"
                                                    value="100"
                                                    class="form-radio w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                                                />
                                                <label
                                                    for="amount-100"
                                                    class="ml-2 text-sm font-semibold text-gray-900 dark:text-white"
                                                    >₱ 100</label
                                                >
                                            </div>
                                            <div class="flex items-center">
                                                <input
                                                    id="amount-300"
                                                    type="radio"
                                                    v-model="rechargeAmount"
                                                    value="300"
                                                    class="form-radio w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                                                />
                                                <label
                                                    for="amount-300"
                                                    class="ml-2 text-sm font-semibold text-gray-900 dark:text-white"
                                                    >₱ 300</label
                                                >
                                            </div>
                                            <div class="flex items-center">
                                                <input
                                                    id="amount-500"
                                                    type="radio"
                                                    v-model="rechargeAmount"
                                                    value="500"
                                                    class="form-radio w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                                                />
                                                <label
                                                    for="amount-500"
                                                    class="ml-2 text-sm font-semibold text-gray-900 dark:text-white"
                                                    >₱ 500</label
                                                >
                                            </div>
                                            <div class="flex items-center">
                                                <input
                                                    id="amount-1000"
                                                    type="radio"
                                                    v-model="rechargeAmount"
                                                    value="1000"
                                                    class="form-radio w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                                                />
                                                <label
                                                    for="amount-1000"
                                                    class="ml-2 text-sm font-semibold text-gray-900 dark:text-white"
                                                    >₱ 1000</label
                                                >
                                            </div>
                                        </div>
                                        <p class="mt-4 text-xs text-red-500">
                                            * Note: A 5% charge will be applied
                                            to your selected recharge amount.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-gray-50 dark:bg-slate-950 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                            >
                                <button
                                    type="button"
                                    :disabled="isLoading"
                                    :class="{
                                        'inline-flex w-full justify-center rounded-md bg-green-200 px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto cursor-not-allowed':
                                            isLoading,
                                        'inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-900 sm:ml-3 sm:w-auto':
                                            !isLoading,
                                    }"
                                    @click="confirmRecharge"
                                >
                                    Confirm
                                </button>
                                <button
                                    type="button"
                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
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
import { ref, defineProps, defineEmits } from "vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { ArrowTopRightOnSquareIcon } from "@heroicons/vue/24/outline";
import axiosClient from "@/axiosClient.js";

const props = defineProps({
    isOpen: Boolean,
});

const emit = defineEmits(["close", "recharge"]);
const rechargeAmount = ref(100);
const isLoading = ref(false);

const closeModal = () => {
    emit("close");
};

const confirmRecharge = async () => {
    try {
        isLoading.value = true;
        const response = await axiosClient.post(
            "/auction/create-recharge-session",
            {
                amount: rechargeAmount.value,
            }
        );
        window.location.href = response.data.checkout_url;
    } catch (error) {
        console.error("Error processing recharge:", error);
        alert("Error processing recharge. Please try again.");
    } finally {
        isLoading.value = false;
    }
};
</script>

<style scoped></style>
