<template>
    <nav
        v-if="links.length > 3"
        class="flex flex-wrap justify-center gap-2 sm:space-x-2"
    >
        <button
            v-for="link in visibleLinks"
            :key="link.label"
            :disabled="!link.url"
            :class="[
                'px-3 py-1 sm:px-4 sm:py-2 border rounded text-sm sm:text-base',
                link.active
                    ? 'bg-blue-500 text-white'
                    : 'bg-gray-200 text-gray-600 hover:bg-gray-300',
                !link.url ? 'cursor-not-allowed opacity-50' : '',
            ]"
            @click="goToPage(link.url)"
        >
            <!-- Customizing Pagination Labels -->
            <span v-html="decodeHtml(link.label)"></span>
        </button>
    </nav>
</template>

<script setup>
import { computed } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    links: Array,
});

// Decode special HTML entities like « and »
const decodeHtml = (html) => {
    const txt = document.createElement("textarea");
    txt.innerHTML = html;
    let decoded = txt.value;

    if (decoded === "«") return "Previous";
    if (decoded === "»") return "Next";

    return decoded;
};

const goToPage = (url) => {
    if (url) router.visit(url);
};

// 👇 Limit visible buttons on small screens
const visibleLinks = computed(() => {
    if (window.innerWidth < 640) {
        // Always show first, last, active, prev, next
        const activeIndex = props.links.findIndex((l) => l.active);
        return props.links.filter((link, i) => {
            return (
                i === 0 || // first
                i === props.links.length - 1 || // last
                i === activeIndex || // current
                i === activeIndex - 1 || // prev
                i === activeIndex + 1 // next
            );
        });
    }
    return props.links;
});
</script>
