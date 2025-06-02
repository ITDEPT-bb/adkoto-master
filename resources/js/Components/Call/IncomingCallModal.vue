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
                <div class="fixed inset-0 bg-black/25" />
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
                                This is a fixed modal message with no slots or
                                customization.
                            </p>
                            <Link
                                :href="
                                    route('callPage.index', {
                                        user: user,
                                        caller: user.id,
                                    })
                                "
                                aria-label="Accept Call"
                            >
                                Answer Call
                            </Link>
                            <!-- <button @click="answerCall">Answer Call</button> -->
                            <div class="mt-4">
                                <button
                                    type="button"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                    @click="emitClose"
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
import { defineProps, defineEmits } from "vue";
import { Link } from "@inertiajs/vue3";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
} from "@headlessui/vue";

const props = defineProps({
    modelValue: Boolean,
    user: {
        type: Object,
        required: true,
    },
});

// const ringtone = new Audio("/audio/ringtone_sound.mp3");
// const time_limit_sound = new Audio("/audio/call_time_limit_sound.wav");
// ringtone.loop = true;
// time_limit_sound.loop = false;

// ringtone.play();

const emit = defineEmits(["update:modelValue"]);

function emitClose() {
    emit("update:modelValue", false);
}

const answerCall = () => {
    // Stop the ringtone
    ringtone.pause();
    ringtone.currentTime = 0;
};
</script>
