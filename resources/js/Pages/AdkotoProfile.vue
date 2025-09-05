<script setup>
import { Head, Link } from "@inertiajs/vue3";
import logo from "/public/img/adkoto.png";

defineProps({
    terms: String,
});
</script>

<template>
    <Head title="Adkoto Profile" />

    <div
        class="font-sans text-gray-900 dark:text-gray-100 antialiased h-full overflow-y-auto"
    >
        <div class="pt-4 bg-gray-100 dark:bg-gray-900">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <!-- Logo -->
                <Link :href="route('adkoto')">
                    <img
                        :src="logo"
                        class="block h-10 sm:h-12 w-auto mb-6"
                        alt="Adkoto Logo"
                    />
                </Link>

                <!-- Adkoto Profile -->
                <div
                    class="w-full sm:max-w-5xl mt-6 p-8 bg-white dark:bg-gray-800 shadow-md rounded-lg"
                >
                    <h1
                        class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-gray-200"
                    >
                        Adkoto Profile
                    </h1>

                    <!-- Adkoto Profile Content -->
                    <div
                        class="prose dark:prose-invert max-w-full px-2 text-gray-700 dark:text-gray-300"
                    >
                        <p class="mb-4">
                            <strong>Adkoto</strong> stands for
                            <em>“Adhikain Ko To”</em>—meaning “This is My
                            Advocacy.” It is an advertising and promotions
                            platform created to uphold and celebrate Filipino
                            heritage, values, and culture in the midst of
                            today’s rapidly evolving technological era,
                            including the rise of Artificial Intelligence.
                        </p>

                        <p class="mb-4">
                            At its core, Adkoto symbolizes that real human
                            interaction remains one of the greatest
                            communication tools ever invented. It highlights
                            genuine connectivity, emphasizing that true human
                            relations will always matter.
                        </p>

                        <p class="mb-4">
                            Guided by our belief in authentic connections, we
                            built this platform with our guiding slogan:
                        </p>

                        <blockquote class="mb-4">
                            “Adkoto—Where Real Humans Connect.”
                        </blockquote>

                        <p class="mb-4">
                            The platform
                            <a
                                href="https://adkoto.com/"
                                target="_blank"
                                class="underline"
                            >
                                www.adkoto.com
                            </a>
                            is marketed and managed by
                            <strong
                                >BB 88 Advertising and Digital Solutions
                                Inc.</strong
                            >, under the authority and sanction of
                            <strong
                                >Adkoto Advertising and Promotion Portal
                                Inc.</strong
                            >
                            management.
                        </p>

                        <p class="mb-4">
                            <strong>Corporate Office:</strong><br />
                            Unit D, Plaza Victoria Building, 2nd Floor, Sto.
                            Rosario Street, Sto. Domingo, 2009 Angeles City,
                            Philippines.
                        </p>

                        <p class="mb-4">
                            <strong>Email:</strong> info@adkoto.com<br />
                            <strong>Marketing Department:</strong>
                            marketing@adkoto.com
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
