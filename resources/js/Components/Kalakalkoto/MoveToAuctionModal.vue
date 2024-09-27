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
                            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                        >
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div
                                        class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10"
                                    >
                                        <ArrowTopRightOnSquareIcon
                                            class="h-6 w-6 text-green-600"
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
                                            Move to Auction your
                                            <span
                                                class="text-blue-400 underline"
                                                >{{ kalakalitem.name }}</span
                                            >
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">
                                                Choose the auction type you want
                                                to apply.
                                            </p>
                                            <div class="flex mt-4">
                                                <div
                                                    class="flex items-center h-5"
                                                >
                                                    <input
                                                        id="helper-radio"
                                                        aria-describedby="helper-radio-text"
                                                        type="radio"
                                                        v-model="auctionType"
                                                        value="normal"
                                                        class="form-radio w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                    />
                                                </div>
                                                <div class="ms-2 text-sm">
                                                    <label
                                                        for="helper-radio"
                                                        class="font-semibold flex justify-between text-gray-900 dark:text-gray-300"
                                                        >Normal Bidding
                                                        <span
                                                            >(₱
                                                            {{
                                                                normalPrice
                                                            }})</span
                                                        ></label
                                                    >
                                                    <p
                                                        id="helper-radio-text"
                                                        class="text-xs font-normal text-start text-gray-500 dark:text-gray-300"
                                                    >
                                                        Choose this option to
                                                        display your item on the
                                                        Normal Bidding List
                                                        Section.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex mt-4">
                                                <div
                                                    class="flex items-center h-5"
                                                >
                                                    <input
                                                        id="helper-radio"
                                                        aria-describedby="helper-radio-text"
                                                        type="radio"
                                                        v-model="auctionType"
                                                        value="live"
                                                        class="form-radio w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                    />
                                                </div>
                                                <div class="ms-2 text-sm">
                                                    <label
                                                        for="helper-radio"
                                                        class="font-semibold flex justify-between text-gray-900 dark:text-gray-300"
                                                        >Live Bidding
                                                        <span
                                                            >(₱
                                                            {{
                                                                livePrice
                                                            }})</span
                                                        ></label
                                                    >
                                                    <p
                                                        id="helper-radio-text"
                                                        class="text-xs font-normal text-start text-gray-500 dark:text-gray-300"
                                                    >
                                                        Choose this option to
                                                        display your item on the
                                                        Live Bidding List
                                                        Section.
                                                    </p>
                                                </div>
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
                                    :disabled="isLoading"
                                    :class="{
                                        'inline-flex w-full justify-center rounded-md bg-green-200 px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto cursor-not-allowed':
                                            isLoading,
                                        'inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-900 sm:ml-3 sm:w-auto':
                                            !isLoading,
                                    }"
                                    @click="confirmAuctionType"
                                >
                                    Confirm
                                </button>
                                <button
                                    type="button"
                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                                    @click="closeModal"
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
</template>

<script setup>
import { ref, computed, defineProps, defineEmits } from "vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { ArrowTopRightOnSquareIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    isOpen: Boolean,
    kalakalitem: Object,
});

const emit = defineEmits(["close"]);

const auctionType = ref("normal");
const isLoading = ref(false);

// const normalPrice = computed(() => props.kalakalitem.price * 0.05);
// const livePrice = computed(() => props.kalakalitem.price * 0.08);
const normalPrice = computed(() => {
    return Math.max(20, Math.round(props.kalakalitem.price * 0.05));
});

const livePrice = computed(() => {
    return Math.max(30, Math.round(props.kalakalitem.price * 0.08));
});

const closeModal = () => {
    emit("close");
};

const confirmAuctionType = async () => {
    try {
        isLoading.value = true;

        let amount;
        if (auctionType.value === "normal") {
            amount = Math.max(2000, Math.round(normalPrice.value * 100));
        } else if (auctionType.value === "live") {
            amount = Math.max(3000, Math.round(livePrice.value * 100));
        }

        // amount = Math.round(amount);
        // amount = Math.max(2000, Math.round(amount));

        const response = await axios.post("/auction/create-checkout-session", {
            item_id: props.kalakalitem.id,
            auction_type: auctionType.value,
            amount: amount,
        });

        window.location.href = response.data.checkout_url;
    } catch (error) {
        console.error("Error creating checkout session:", error);
    } finally {
        isLoading.value = false;
        // closeModal();
    }
};
</script>

<style scoped></style>
