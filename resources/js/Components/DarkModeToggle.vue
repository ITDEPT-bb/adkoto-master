<script setup>
import { ref, onMounted } from "vue";
import { MoonIcon, SunIcon } from "@heroicons/vue/24/solid";

const isDark = ref(false);

const toggleDarkMode = () => {
    const html = document.documentElement;
    isDark.value = !isDark.value;

    if (isDark.value) {
        html.classList.add("dark");
        localStorage.setItem("darkMode", "1");
    } else {
        html.classList.remove("dark");
        localStorage.setItem("darkMode", "0");
    }
};

// Initialize theme on mount
onMounted(() => {
    const darkMode = localStorage.getItem("darkMode");
    if (darkMode === "1") {
        isDark.value = true;
        document.documentElement.classList.add("dark");
    } else {
        isDark.value = false;
        document.documentElement.classList.remove("dark");
    }
});
</script>

<template>
    <button @click="toggleDarkMode" class="dark:text-white">
        <component :is="isDark ? SunIcon : MoonIcon" class="w-5 h-5" />
    </button>
</template>
