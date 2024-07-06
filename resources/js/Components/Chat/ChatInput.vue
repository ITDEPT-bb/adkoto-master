<template>
    <div class="mt-4 px-4">
        <div class="relative">
            <textarea
                v-model="newMessage"
                @keyup.enter="sendMessage"
                class="w-full p-2 mb-3 border rounded-lg resize-none block"
                placeholder="Type a message..."
            ></textarea>
            <button
                @click="sendMessage"
                class="absolute inset-y-0 right-0 px-4 py-2 bg-blue-500 text-white rounded-md"
            >
                Send
            </button>
        </div>
    </div>
</template>

<script setup>
import { defineProps, useContext } from "vue";

const props = defineProps({
    newMessage: String,
});

const { emit } = useContext();

const sendMessage = () => {
    emit("send");
};
</script>
