<script setup lang="ts">
import Accordion from "@/Components/Accordion.vue";
import { Head, Link } from "@inertiajs/vue3";

const faqs = [
    {
        title: "What is Adkoto?",
        text: "Adkoto is the first Filipino-developed social media platform that combines communication, online shopping, and advertising features. It allows you to connect with friends and family, discover and purchase products on Kalakalkoto, and promote your business or personal brand through advertisements.",
        active: false,
    },
    {
        title: "Do I need an account to use Adkoto?",
        text: "Yes, you will need to create a free account to access all of Adkoto's features, including Tribekoto, Kalakalkoto, and advertising functionalities.",
        active: false,
    },
    {
        title: "How do I create an account?",
        text: "Signing up for Adkoto is easy! Simply visit our website and click on the 'Register' button. You will be prompted to provide your basic information, such as your name, email address, and desired username.",
        active: false,
    },
    {
        title: "Is Adkoto available as a mobile app?",
        text: "Yes, Adkoto is available as a free mobile app for both iOS and Android devices. You can download it from the App Store or Google Play Store.",
        active: false,
    },
    {
        title: "Is Adkoto free to use?",
        text: "Yes, creating an Adkoto account and using its core features like Tribekoto and browsing Kalakalkoto listings are completely free. However, advertising on Adkoto and purchasing products on Kalakalkoto involve transaction fees depending on the seller's pricing and chosen payment method.",
        active: false,
    },
    {
        title: "What are the different features of Adkoto?",
        text: "Adkoto offers three main features: Tribekoto, Kalakalkoto, and Adkoto for advertising. Tribekoto allows for social interactions, Kalakalkoto supports online shopping, and Adkoto offers targeted advertising.",
        active: false,
    },
    {
        title: "What makes Tribekoto unique compared to other social media platforms?",
        text: "Tribekoto integrates a sense of community with its focus on Filipino culture and language, providing a platform for Filipinos to connect and share experiences in a familiar online space.",
        active: false,
    },
    {
        title: "How do I find friends on Tribekoto?",
        text: "You can find friends on Tribekoto by searching for their usernames or email addresses, or through suggested connections based on your interests and mutual friends.",
        active: false,
    },
    {
        title: "Can I create private groups on Tribekoto?",
        text: "Yes, you can create private groups on Tribekoto. This allows you to control who can join and share content within a specific community you create.",
        active: false,
    },
    {
        title: "How do I post different content types (text, images, videos) on Tribekoto?",
        text: "Tribekoto offers a user-friendly interface for creating posts. You can simply choose the type of content (text, image, or video) you want to share and add a caption or description.",
        active: false,
    },
    {
        title: "What payment methods are accepted on Kalakalkoto?",
        text: "Kalakalkoto offers a variety of payment methods including debit cards, credit cards, and popular Philippine e-wallets. Cash on delivery (COD) may also be available depending on the seller.",
        active: false,
    },
    {
        title: "Does Kalakalkoto offer buyer protection policies?",
        text: "Yes, Kalakalkoto offers buyer protection policies such as secure payment processing, dispute resolution procedures, and potential money-back guarantees.",
        active: false,
    },
    {
        title: "Can I track my orders on Kalakalkoto?",
        text: "Yes, you can track your orders on Kalakalkoto. You will receive updates on your order status within your account, including shipment and delivery details.",
        active: false,
    },
    {
        title: "How do I leave reviews and ratings for sellers on Kalakalkoto?",
        text: "After receiving your order, you can leave reviews and ratings for sellers. This helps other users make informed decisions and provides feedback to the sellers.",
        active: false,
    },
    {
        title: "How much does it cost to advertise on Adkoto?",
        text: "The cost of advertising depends on campaign type, targeting criteria, and budget. Adkoto offers CPC and CPM models, and you can control your advertising budget.",
        active: false,
    },
    {
        title: "Do I need a business license to advertise on Adkoto?",
        text: "Not necessarily. However, if you are promoting a business that requires a license, it is important to comply with local regulations.",
        active: false,
    },
    {
        title: "What advertising formats are available on Adkoto?",
        text: "Adkoto offers display ads, video ads, and sponsored content. Stay updated for new formats by checking Adkoto's advertiser resources.",
        active: false,
    },
    {
        title: "How can I target my ads to specific audiences on Adkoto?",
        text: "You can target your ads based on age, location, interests, and online behavior. This helps ensure your ads reach the most relevant audience.",
        active: false,
    },
    {
        title: "Does Adkoto provide analytics tools to track ad performance?",
        text: "Yes, Adkoto provides analytics tools to track impressions, clicks, conversions, and reach, helping you optimize your advertising campaigns.",
        active: false,
    },
];
</script>

<template>
    <Head title="Copyright Notice" />
    <section class="h-full overflow-y-auto">
        <main class="relative flex flex-col justify-center bg-slate-50">
            <div class="w-full max-w-2xl mx-auto px-4 md:px-6 py-24">
                <h1 class="text-2xl font-bold text-slate-900 mb-4">
                    Adkoto FAQs
                </h1>

                <div class="divide-y divide-slate-200">
                    <template v-for="(faq, index) in faqs">
                        <Accordion
                            :title="faq.title"
                            :id="`faqs-${index}`"
                            :active="faq.active"
                        >
                            {{ faq.text }}
                        </Accordion>
                    </template>
                </div>
            </div>
        </main>
    </section>
</template>
