<template>
    <Head title="About Us" />

    <div
        class="h-full mx-auto overflow-hidden flex flex-col bg-gray-100 dark:bg-gray-800"
    >
        <main class="flex-1 overflow-y-scroll">
            <Hero />
            <AdsSection :featuredAds="featuredAds" />
            <AboutUs />
            <AdhikainKoto />
            <Kalakalkoto />
            <Auction />
            <AdsSection :featuredAds="featuredAds" />
            <Tribekoto />
            <Cards />
            <Testimonials />
            <ContactUs />
            <Footer />
        </main>
    </div>
</template>

<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import Hero from "@/Components/About/Hero.vue";
import AdsSection from "@/Components/About/AdsSection.vue";
import { ref } from "vue";
import AboutUs from "@/Components/About/AboutUs.vue";
import AdhikainKoto from "@/Components/About/AdhikainKoto.vue";
import Kalakalkoto from "@/Components/About/Kalakalkoto.vue";
import Auction from "@/Components/About/Auction.vue";
import Tribekoto from "@/Components/About/Tribekoto.vue";
import Footer from "@/Components/About/Footer.vue";
import Cards from "@/Components/About/Cards.vue";
import ContactUs from "@/Components/About/ContactUs.vue";
import Testimonials from "@/Components/About/Testimonials.vue";

const { props } = usePage();
const featuredAds = ref(props.featuredAds);
</script>
