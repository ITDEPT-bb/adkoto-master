<template>
    <nav v-if="links.length > 3" class="flex justify-center space-x-2">
        <button
            v-for="link in links"
            :key="link.label"
            :disabled="!link.url"
            :class="[
                'px-4 py-2 border rounded',
                link.active
                    ? 'bg-blue-500 text-white'
                    : 'bg-gray-200 text-gray-600',
                !link.url ? 'cursor-not-allowed' : '',
            ]"
            @click="goToPage(link.url)"
        >
            <!-- Customizing Pagination Labels by replacing HTML entities -->
            <span v-html="decodeHtml(link.label)"></span>
        </button>
    </nav>
</template>

<script setup>
import { router } from "@inertiajs/vue3";

const props = defineProps({
    links: Array,
});

// Helper function to decode HTML entities like &laquo; and &raquo;
const decodeHtml = (html) => {
    const txt = document.createElement("textarea");
    txt.innerHTML = html;
    let decoded = txt.value;

    // Replace &laquo; and &raquo; with custom text
    if (decoded === "«") return "Previous";
    if (decoded === "»") return "Next";

    return decoded;
};

const goToPage = (url) => {
    if (url) {
        router.visit(url);
    }
};
</script>
