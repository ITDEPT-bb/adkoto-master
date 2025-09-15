<template>
    <footer class="bg-brand-blue text-white py-8">
        <div
            class="max-w-screen-xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 px-4"
        >
            <!-- First Column: Logo -->
            <section class="flex flex-col space-y-4">
                <div>
                    <img
                        src="/assets/logo/logo_footer.png"
                        alt="Tribekoto Logo"
                        class="w-48 sm:w-60 md:w-72 lg:w-96 border-b-4 border-brand-blue"
                    />
                </div>
            </section>

            <!-- Second Column: Useful Links -->
            <section class="space-y-2">
                <h3 class="font-semibold text-lg">Useful Links</h3>
                <ul class="space-y-2">
                    <li>
                        <button
                            @click="scrollToSection('home')"
                            class="text-white hover:text-brand-green"
                        >
                            Home
                        </button>
                    </li>
                    <li>
                        <button
                            @click="scrollToSection('about-us')"
                            class="text-white hover:text-brand-green"
                        >
                            About us
                        </button>
                    </li>
                    <li>
                        <button
                            @click="scrollToSection('features')"
                            class="text-white hover:text-brand-green"
                        >
                            Features
                        </button>
                    </li>
                    <li>
                        <button
                            @click="scrollToSection('testimonials')"
                            class="text-white hover:text-brand-green"
                        >
                            Testimonials
                        </button>
                    </li>
                    <li>
                        <button
                            @click="scrollToSection('contact-us')"
                            class="text-white hover:text-brand-green"
                        >
                            Contact us
                        </button>
                    </li>
                </ul>
            </section>

            <!-- Third Column: Legal Policies -->
            <section class="space-y-2">
                <h3 class="font-semibold text-lg">
                    Legal Policies & Standards
                </h3>
                <ul class="space-y-2">
                    <li>
                        <Link
                            :href="route('adkotoprofile.show')"
                            class="text-white hover:text-brand-green"
                            >Adkoto Profile</Link
                        >
                    </li>
                    <li>
                        <Link
                            :href="route('policy.show')"
                            class="text-white hover:text-brand-green"
                            >Privacy Policy</Link
                        >
                    </li>
                    <li>
                        <Link
                            :href="route('terms.show')"
                            class="text-white hover:text-brand-green"
                            >Terms and Conditions</Link
                        >
                    </li>
                    <li>
                        <Link
                            :href="route('copyright.show')"
                            class="text-white hover:text-brand-green"
                            >Copyrights</Link
                        >
                    </li>
                    <li>
                        <Link
                            :href="route('faqs.show')"
                            class="text-white hover:text-brand-green"
                            >FAQs</Link
                        >
                    </li>
                </ul>
            </section>

            <!-- Fourth Column: Newsletter -->
            <section class="space-y-4">
                <h3 class="text-lg font-semibold">Our Newsletter</h3>
                <form
                    action="#"
                    class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-2"
                >
                    <input
                        type="email"
                        placeholder="Enter your email"
                        class="px-4 py-2 rounded-md w-full sm:w-64"
                    />
                    <button
                        type="submit"
                        class="bg-brand-green text-white px-4 py-2 rounded-md hover:bg-green-600 w-full sm:w-auto"
                    >
                        Subscribe
                    </button>
                </form>
            </section>
        </div>
    </footer>

    <!-- Bottom copyright bar -->
    <div
        class="bg-brand-green text-white flex flex-col sm:flex-row justify-between items-center py-2 px-6 text-sm"
    >
        <p class="mb-1 sm:mb-0">
            ©{{ currentYear }} Adkoto, All Rights Reserved.
        </p>
        <p class="text-center">
            Designed & Developed by BB 88 Advertising and Digital Solutions Inc.
        </p>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

function scrollToSection(id) {
    const section = document.getElementById(id);
    if (section) {
        section.scrollIntoView({ behavior: "smooth" });
    }
}

const currentYear = new Date().getFullYear();
</script>
