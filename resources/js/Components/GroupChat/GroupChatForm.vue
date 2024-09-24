<script setup>
import InputTextarea from "@/Components/InputTextarea.vue";
import Checkbox from "@/Components/Checkbox.vue";
import TextInput from "@/Components/TextInput.vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

defineProps({
    form: Object,
});

const editor = ClassicEditor;
const editorConfig = {
    toolbar: [
        "bold",
        "italic",
        "|",
        "bulletedList",
        "numberedList",
        "|",
        "heading",
        "|",
        "outdent",
        "indent",
        "|",
        "link",
        "|",
        "blockQuote",
    ],
};
</script>

<template>
    <div class="space-y-6">
        <div class="mb-3 dark:text-gray-100">
            <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                >Group Chat Name</label
            >
            <TextInput
                type="text"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                v-model="form.name"
                required
                autofocus
            />
        </div>

        <!-- <div class="mb-3 dark:text-gray-100">
            <label class="flex items-center">
                <Checkbox
                    name="remember"
                    v-model:checked="form.auto_approval"
                />
                <span
                    class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300"
                    >Enable Auto Approval</span
                >
            </label>
        </div> -->

        <!-- <div class="mb-3 dark:text-gray-100">
            <label
                for="group_status"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                >Group Status</label
            >
            <select
                id="group_status"
                v-model="form.group_status"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            >
                <option value="public">Public</option>
                <option value="private">Private</option>
            </select>
        </div> -->

        <div class="mb-3 dark:text-gray-100">
            <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                >Group Chat Desription</label
            >
            <InputTextarea
                v-model="form.description"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            ></InputTextarea>
        </div>
    </div>
</template>

<style scoped></style>
