<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

const mobileOpen = ref(false);

function toggleMenu() {
    mobileOpen.value = !mobileOpen.value;
}

function scrollToSection(id) {
    const section = document.getElementById(id);
    if (section) {
        section.scrollIntoView({ behavior: "smooth" });
    }
}
</script>

<template>
    <header class="pt-6">
        <div class="max-w-7xl mx-auto">
            <div
                class="bg-white rounded-full border-4 border-brand-blue shadow-xl flex items-center justify-between gap-6 px-6 py-2 relative"
            >
                <!-- Logo -->
                <div class="flex items-center">
                    <img
                        src="/assets/logo/logo_header.png"
                        alt="Adkoto Logo"
                        class="h-10 sm:h-12"
                    />
                </div>

                <!-- Desktop nav -->
                <nav
                    class="hidden md:flex flex-1 justify-center items-center space-x-6 text-gray-700 font-medium"
                >
                    <button
                        @click="scrollToSection('home')"
                        class="bg-brand-green text-white px-4 py-1 rounded-md font-semibold shadow-sm shadow-green-200/40"
                    >
                        Home
                    </button>
                    <button
                        @click="scrollToSection('about-us')"
                        class="hover:text-blue-700 transition"
                    >
                        About us
                    </button>
                    <button
                        @click="scrollToSection('features')"
                        class="hover:text-blue-700 transition"
                    >
                        Features
                    </button>
                    <button
                        @click="scrollToSection('testimonials')"
                        class="hover:text-blue-700 transition"
                    >
                        Testimonials
                    </button>
                    <button
                        @click="scrollToSection('contact-us')"
                        class="hover:text-blue-700 transition"
                    >
                        Contact us
                    </button>
                </nav>

                <!-- Desktop button -->
                <div class="hidden md:flex items-center">
                    <Link
                        :href="route('auction.login')"
                        class="inline-flex items-center px-6 py-1 rounded-full bg-brand-blue text-white font-semibold shadow-md hover:bg-blue-700 transition"
                    >
                        Log in
                    </Link>
                </div>

                <!-- Mobile menu button -->
                <button
                    @click="toggleMenu"
                    class="md:hidden flex items-center justify-center p-2 rounded-full border border-gray-300"
                >
                    <svg
                        v-if="!mobileOpen"
                        class="w-6 h-6 text-gray-700"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                    <svg
                        v-else
                        class="w-6 h-6 text-gray-700"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>

            <!-- Mobile menu dropdown -->
            <transition name="fade">
                <div
                    v-if="mobileOpen"
                    class="md:hidden mt-3 bg-white border-2 border-brand-blue rounded-xl shadow-lg p-4 space-y-3"
                >
                    <button
                        @click="scrollToSection('home')"
                        class="block px-4 py-2 rounded-md font-semibold bg-brand-green text-white"
                    >
                        Home
                    </button>
                    <button
                        @click="scrollToSection('about-us')"
                        class="block px-4 py-2 hover:text-blue-700 transition"
                    >
                        About us
                    </button>
                    <button
                        @click="scrollToSection('features')"
                        class="block px-4 py-2 hover:text-blue-700 transition"
                    >
                        Features
                    </button>
                    <button
                        @click="scrollToSection('testimonials')"
                        class="block px-4 py-2 hover:text-blue-700 transition"
                    >
                        Testimonials
                    </button>
                    <button
                        @click="scrollToSection('contact-us')"
                        class="block px-4 py-2 hover:text-blue-700 transition"
                    >
                        Contact us
                    </button>

                    <!-- Mobile login button -->
                    <Link
                        :href="route('auction.login')"
                        class="block text-center px-6 py-2 rounded-full bg-brand-blue text-white font-semibold shadow-md hover:bg-blue-700 transition"
                    >
                        Log in
                    </Link>
                </div>
            </transition>
        </div>
    </header>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
