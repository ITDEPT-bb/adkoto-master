<script setup>
import { Head, Link } from "@inertiajs/vue3";
import logo from "/public/img/adkoto.png";

defineProps({
    terms: String,
});
</script>

<template>
    <Head title="Copyright Notice" />

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

                <!-- Copyright Notice Container -->
                <div
                    class="w-full sm:max-w-5xl mt-6 p-8 bg-white dark:bg-gray-800 shadow-md rounded-lg"
                >
                    <h1
                        class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-gray-200"
                    >
                        Copyright Notice
                    </h1>

                    <!-- Copyright Content -->
                    <div
                        class="prose dark:prose-invert max-w-full px-2 text-gray-700 dark:text-gray-300"
                    >
                        <p class="mb-4">
                            At Adkoto, we're passionate about fostering a
                            vibrant online space that celebrates creativity and
                            empowers users. This Copyright Notice outlines the
                            ownership of content displayed on our platform and
                            how to use it responsibly to ensure a thriving and
                            respectful community for everyone.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Copyrighted Material:
                        </h4>
                        <p class="mb-4">
                            The content you see on Adkoto, including text, posts
                            (text, images, videos), Tribekoto group content, and
                            other creative works, is protected by copyright laws
                            and international treaties. This material is the
                            exclusive property of Adkoto or its respective
                            licensors.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Copyrighted Material:
                        </h4>
                        <p class="mb-4">
                            The content you see on Adkoto, including text, posts
                            (text, images, videos), Tribekoto group content, and
                            other creative works, is protected by copyright laws
                            and international treaties. This material is the
                            exclusive property of Adkoto or its respective
                            licensors.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Using Adkoto Responsibly:
                        </h4>
                        <p class="mb-4">
                            You can browse Adkoto and access its content for
                            personal, non-commercial purposes. You may also
                            share snippets of our content with proper
                            attribution, including a clear link back to the
                            specific Adkoto webpage source.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Respecting Copyrights:
                        </h4>
                        <p class="mb-4">
                            Adkoto takes copyright infringement seriously. If
                            you believe that any content displayed on our
                            platform infringes upon your copyright, please
                            contact us immediately with a detailed notification.
                            We will take appropriate action in accordance with
                            applicable laws.
                        </p>

                        <h2
                            class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Sharing responsibly on Adkoto
                        </h2>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Tribekoto: Your Creative Canvas
                        </h4>
                        <p class="mb-4">
                            The content you share on Tribekoto, including text,
                            images, and videos, remains your property. You are
                            responsible for ensuring you have the rights to
                            share any content you post. Remember, Tribekoto is
                            your space to showcase your creativity, but be
                            mindful of using content that belongs to others.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Kalakalkoto Marketplace: Follow the Guidelines
                        </h4>
                        <p class="mb-4">
                            When using Kalakalkoto, our online marketplace, be
                            sure to follow the specific terms and conditions
                            outlined by sellers regarding product descriptions,
                            images, and other content. Always ensure you have
                            the rights to use any content you upload related to
                            your products.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Sharing on External Platforms: Be Mindful of Rights
                        </h4>
                        <p class="mb-4">
                            If you share your work created on Adkoto or inspired
                            by it on other platforms, make sure you have the
                            necessary rights to use any resources you
                            incorporate, including fonts, stock photos, and
                            elements not originally created by you. It's always
                            best to check the license terms before sharing.
                        </p>
                        <p class="mb-4">
                            By respecting copyrights and sharing responsibly, we
                            can cultivate a thriving online community on Adkoto.
                            This fosters creativity, innovation, and safe spaces
                            for everyone to express themselves. We appreciate
                            your cooperation and encourage you to contact us if
                            you have any further questions about copyright or
                            sharing content on Adkoto.
                        </p>
                        <p class="mb-4">
                            We hope this clarifies copyright considerations when
                            using Adkoto and creating your own content. If you
                            have any further questions, please don't hesitate to
                            contact us
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
