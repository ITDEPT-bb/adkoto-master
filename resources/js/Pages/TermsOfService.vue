<script setup>
import { Head, Link } from "@inertiajs/vue3";
import logo from "/public/img/adkoto.png";

defineProps({
    terms: String,
});
</script>

<template>
    <Head title="Terms and Conditions" />

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

                <!-- Terms of Service Container -->
                <div
                    class="w-full sm:max-w-5xl mt-6 p-8 bg-white dark:bg-gray-800 shadow-md rounded-lg"
                >
                    <h1
                        class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-gray-200"
                    >
                        Terms of Service and Conditions
                    </h1>

                    <!-- Terms Content -->
                    <div
                        class="prose dark:prose-invert max-w-full px-2 text-gray-700 dark:text-gray-300"
                    >
                        <p class="mb-4">
                            Welcome to Adkoto, the first Filipino-developed
                            social media platform that empowers you to connect,
                            shop, and express yourself! We're thrilled to have
                            you join our vibrant online community. These Terms
                            and Conditions establish a foundation for a positive
                            and productive experience for everyone who uses
                            Adkoto.
                        </p>
                        <p class="mb-4">
                            By accessing or using Adkoto, you agree to be bound
                            by these Terms and Conditions. We believe in clear
                            communication, so if you have any questions, please
                            don't hesitate to contact us before proceeding.
                        </p>
                        <h2
                            class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Building a Thriving Community on Adkoto
                        </h2>
                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Respectful Interactions:
                        </h4>
                        <p class="mb-4">
                            Adkoto is a hub for creativity, collaboration, and
                            connection. We encourage respectful behavior and
                            dialogue in all interactions. Activities deemed
                            offensive, harassing, or illegal will not be
                            tolerated. This includes, but is not limited to:
                        </p>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>Hate speech or discrimination</li>
                            <li>Threats or bullying</li>
                            <li>Spam or unsolicited messages</li>
                            <li>Sharing of illegal content</li>
                            <li>Promotion of violence or harmful activities</li>
                        </ul>
                        <h2
                            class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Content Ownership:
                        </h2>
                        <p class="mb-4">
                            The content displayed on Adkoto, including text,
                            posts (text, images, videos), Tribekoto group
                            content, product listings on Kalakalkoto,
                            advertisements, and even music, is protected by
                            copyright laws and international treaties. This
                            material belongs to either Adkoto or the respective
                            creators and rights holders who share their work on
                            our platform.
                        </p>
                        <h2
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Here's what you can do:
                        </h2>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>
                                Feel free to browse our platform and access its
                                features for personal, non-commercial purposes.
                            </li>
                            <li>
                                Share snippets of content you find interesting,
                                but always remember to attribute the source with
                                a clear link back to the original Adkoto
                                webpage.
                            </li>
                        </ul>
                        <h2
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Here's what you can't do:
                        </h2>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>
                                Copy, distribute, modify, or reproduce content
                                without permission from the rights holder.
                            </li>
                            <li>
                                Upload content that violates copyright or
                                intellectual property rights of others.
                            </li>
                        </ul>
                        <h2
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Authorized Use:
                        </h2>
                        <p class="mb-4">
                            Adkoto is your one-stop shop for connection,
                            discovery, and self-expression. Here's how you can
                            use our platform responsibly:
                        </p>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>
                                <span class="font-bold">Tribekoto:</span> Share
                                your thoughts, experiences, and creativity with
                                friends and family. Join groups centered around
                                your interests and have meaningful discussions.
                                Remember, Tribekoto content remains yours, but
                                ensure you have the rights to share anything you
                                post.
                            </li>
                            <li>
                                <span class="font-bold">Kalakalkoto:</span>
                                Discover and purchase unique products from
                                Filipino sellers. Be sure to follow the specific
                                terms and conditions outlined by sellers
                                regarding product descriptions, images, and
                                other content. Always ensure you have the rights
                                to use any content you upload related to your
                                products if you're a seller.
                            </li>
                            <li>
                                <span>Adkoto (Advertising):</span> Businesses
                                and individuals can promote their products,
                                services, or brands through targeted
                                advertisements within Adkoto. Please refer to
                                our separate Advertising Guidelines for detailed
                                information on creating and running ads on our
                                platform.
                            </li>
                        </ul>

                        <h2
                            class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Your Responsibilities
                        </h2>
                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Account Management:
                        </h4>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>
                                If you create an account on Adkoto, you are
                                responsible for providing accurate and complete
                                information.
                            </li>
                            <li>
                                It is your responsibility to safeguard your
                                login credentials and keep your account
                                information secure.
                            </li>
                        </ul>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Staying Informed:
                        </h4>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>
                                <span class="font-bold"
                                    >Email Communication:</span
                                >
                                We primarily use email to communicate important
                                announcements, updates, and valuable resources
                                related to Adkoto and the online community.
                                Please ensure your email address associated with
                                Adkoto is current and active.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Staying Up-to-Date:</span
                                >
                                In addition to email, we recommend regularly
                                checking our website and following us on social
                                media to stay informed about new features,
                                upcoming events, and exciting developments
                                within Adkoto.
                            </li>
                        </ul>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Taking Responsibility:
                        </h4>
                        <p class="mb-4">
                            It's your responsibility to stay informed about
                            Adkoto updates. We are not liable for any
                            consequences arising from your inability to receive
                            or respond to notifications sent through email, the
                            website, or social media.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Addressing Liability:
                        </h4>
                        <p class="mb-4">
                            Adkoto, its staff, officers, directors, and
                            representatives cannot be held responsible for any
                            loss, harm, or damages arising from your use of our
                            platform. This includes direct, indirect,
                            incidental, or consequential damages. This
                            limitation of liability applies regardless of the
                            legal basis for the claim.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Account Termination:
                        </h4>
                        <p class="mb-4">
                            Adkoto reserves the right to terminate user accounts
                            or services under certain circumstances,
                            particularly in cases where the Terms and Conditions
                            are violated. Whenever possible, we will provide
                            users with advance notice and the reason for
                            termination. However, we also reserve the right to
                            take immediate action in certain situations. Adkoto
                            values open communication and may offer an appeals
                            process for users who wish to challenge a service or
                            account termination decision.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Third-Party Links:
                        </h4>
                        <p class="mb-4">
                            Our platform may contain links to external
                            resources, such as industry blogs, online tutorials,
                            or seller portfolios. Adkoto is not responsible for
                            the content or actions of these third-party sites.
                            We encourage you to review the privacy policies and
                            terms of use of any external links you visit, as
                            they operate independently of Adkoto.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Building a Vibrant Future Together
                        </h4>
                        <p class="mb-4">
                            By remaining informed, engaging responsibly, and
                            respecting copyrights, you contribute to a thriving
                            online community on Adkoto. We encourage you to
                            explore all that we offer and begin on a rewarding
                            journey of connection, discovery, and
                            self-expression with us! Here are some additional
                            ways you can be a part of the Adkoto community:
                        </p>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>
                                <span class="font-bold"
                                    >Create High-Quality Content:</span
                                >
                                Share your unique perspective and talents by
                                creating engaging posts, informative
                                discussions, or eye-catching product listings on
                                Kalakalkoto.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Support Filipino Sellers:</span
                                >
                                Discover talented artisans and entrepreneurs on
                                Kalakalkoto. Leaving positive reviews and
                                feedback can significantly help small businesses
                                grow.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Promote Respectful Interactions:</span
                                >
                                If you encounter any content or behavior that
                                violates our Terms and Conditions, you can
                                report it directly through Adkoto. We take
                                reports seriously and will investigate
                                accordingly.
                            </li>
                        </ul>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Dispute Resolution
                        </h4>
                        <p class="mb-4">
                            Adkoto strives to provide a fair and secure
                            environment for all users. If you encounter any
                            disputes with other users or have concerns about
                            content displayed on our platform, we encourage you
                            to attempt to resolve the issue directly with the
                            other party involved. However, if a direct
                            resolution is not possible, you can report the issue
                            to Adkoto. We will review the situation and take
                            appropriate action based on our Terms and
                            Conditions.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Fair Use
                        </h4>
                        <p class="mb-4">
                            Adkoto acknowledges the concept of fair use, which
                            permits limited use of copyrighted material without
                            permission for purposes such as commentary,
                            criticism, parody, news reporting, teaching, or
                            scholarship. However, determining fair use can be
                            complex. If you are unsure whether your use of
                            copyrighted material on Adkoto qualifies as fair
                            use, we recommend seeking legal advice.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Copyrights and DMCA Takedowns
                        </h4>
                        <p class="mb-4">
                            Adkoto respects the intellectual property rights of
                            others. If you believe that content displayed on
                            Adkoto infringes on your copyright, you can submit a
                            Digital Millennium Copyright Act (DMCA) takedown
                            notice to us. We will review the notification and
                            take appropriate action according to the DMCA.
                            Please refer to our designated DMCA webpage for
                            detailed instructions on submitting a takedown
                            notice.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Protecting Your Privacy
                        </h4>
                        <p class="mb-4">
                            Adkoto takes your privacy seriously. Please refer to
                            our separate Privacy Policy for detailed information
                            about how we collect, use, and disclose your
                            personal data.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Changes to the Terms and Conditions
                        </h4>
                        <p class="mb-4">
                            Adkoto reserves the right to modify the content and
                            functionality of our platform, as well as update
                            these Terms and Conditions periodically. We will
                            post any changes on our website and notify you
                            through email if necessary. Your continued use of
                            Adkoto following any modifications constitutes your
                            acceptance of the changes.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Termination
                        </h4>
                        <p class="mb-4">
                            Adkoto reserves the right to terminate your access
                            to our platform for any violation of these Terms and
                            Conditions.
                        </p>
                        <p class="mb-4">
                            If you have any questions about these Terms and
                            Conditions, please don't hesitate to contact us. We
                            value open communication and are here to assist you.
                        </p>

                        <p class="mb-4 font-bold">
                            Thank you for choosing Adkoto! We look forward to
                            being a part of your online journey.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Additional Considerations
                        </h4>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>
                                You may want to include a section on
                                <span class="font-bold">Governing Law</span>
                                specifying which jurisdiction's laws will apply
                                in case of legal disputes.
                            </li>
                            <li>
                                Consider adding a disclaimer regarding
                                <span class="font-bold"
                                    >Disclaimer of Warranties</span
                                >. This clarifies that Adkoto is provided "as
                                is" without any express or implied warranties.
                            </li>
                            <li>
                                You may also want to include a section on
                                <span class="font-bold">Indemnification</span>
                                outlining circumstances where a user might be
                                liable for harm caused to Adkoto or other users.
                            </li>
                        </ul>

                        <p class="mb-4 font-bold">
                            By incorporating these additional sections, you can
                            create a more comprehensive and informative Terms
                            and Conditions document for Adkoto.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
