<template>
    <TransitionRoot appear :show="modelValue" as="template">
        <Dialog as="div" class="relative z-10" @close="emitClose">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/95" />
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
                            class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <p class="text-gray-700 text-sm">
                                You Have an Incoming Call from
                            </p>
                            <div
                                class="flex items-center gap-3 mt-4 p-3 bg-blue-50 rounded-lg shadow"
                            >
                                <img
                                    :src="
                                        user.avatar_url ||
                                        '/images/default-avatar.png'
                                    "
                                    alt="User Avatar"
                                    class="w-12 h-12 rounded-full object-cover border border-blue-200"
                                />
                                <div class="flex flex-col items-start">
                                    <span class="font-semibold text-blue-900">{{
                                        user.name
                                    }}</span>
                                    <span class="text-xs text-gray-500"
                                        >is calling you...</span
                                    >
                                </div>
                            </div>
                            <Link
                                :href="
                                    route('callPage.index', {
                                        user: user,
                                        caller: user.id,
                                    })
                                "
                                aria-label="Accept Call"
                                class="mt-4 inline-flex items-center justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                            >
                                Answer Call
                            </Link>
                            <button
                                class="mt-4 ml-2 inline-flex items-center justify-center rounded-md border border-transparent bg-red-100 px-4 py-2 text-sm font-medium text-red-900 hover:bg-red-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                                @click="declineCall"
                                aria-label="Decline Call"
                            >
                                Decline
                            </button>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
import { Link } from "@inertiajs/vue3";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
} from "@headlessui/vue";
import axios from "axios";

const props = defineProps({
    modelValue: Boolean,
    user: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue"]);

function emitClose() {
    emit("update:modelValue", false);
}

const declineCall = async () => {
    const userId = props.user.id;

    await axios.post("/agora/decline-call", {
        to_user_id: userId,
    });
    emitClose();
};
</script>
