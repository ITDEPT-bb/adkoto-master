<template>
    <Head title="About Us" />

    <div
        class="h-full mx-auto overflow-hidden flex flex-col bg-gray-100 dark:bg-gray-800"
    >
        <main class="flex-1 overflow-y-scroll">
            <Hero id="home" data-aos="zoom-in" />
            <AdsSection :featuredAds="featuredAds" data-aos="fade-up" />
            <AboutUs id="about-us" data-aos="fade-up" />
            <AdhikainKoto id="features" data-aos="fade-up" />
            <Kalakalkoto data-aos="fade-up" />
            <Auction :auctions="activeAuctions" data-aos="fade-up" />
            <AdsSection :featuredAds="featuredAds" data-aos="fade-up" />
            <Tribekoto data-aos="fade-up" />
            <Cards data-aos="fade-up" />
            <Testimonials id="testimonials" data-aos="fade-up" />
            <ContactUs id="contact-us" data-aos="fade-up" />
            <Footer />
        </main>
    </div>
</template>

<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import Hero from "@/Components/About/Hero.vue";
import AdsSection from "@/Components/About/AdsSection.vue";
import { ref, onMounted, onBeforeUnmount, nextTick } from "vue";
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
const activeAuctions = ref(props.activeAuctions);

import AOS from "aos";
import "aos/dist/aos.css";

const AOS_ONCE = false;
const AOS_DURATION = 700;
const OBSERVER_THRESHOLD = 0.12;
const ROOT_MARGIN = "0px 0px -10% 0px";

let containerObserver = null;
let mutationObserver = null;
let scrollHandler = null;

function observeAosElements(rootContainer) {
    if (!containerObserver || !rootContainer) return;

    const els = Array.from(rootContainer.querySelectorAll("[data-aos]")).filter(
        (el) => !el.__aos_observed
    );

    els.forEach((el) => {
        el.__aos_observed = true;

        if (!el.classList.contains("aos-init")) el.classList.add("aos-init");
        if (!AOS_ONCE) el.classList.remove("aos-animate");

        containerObserver.observe(el);
    });
}

onMounted(async () => {
    await nextTick();

    const main = document.querySelector("main");
    if (!main) {
        console.warn("[AOS] custom scroll container <main> not found.");
        return;
    }

    AOS.init({
        once: AOS_ONCE,
        duration: AOS_DURATION,
    });

    containerObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                const el = entry.target;
                if (entry.isIntersecting) {
                    el.classList.add("aos-animate");
                } else {
                    if (!AOS_ONCE) el.classList.remove("aos-animate");
                }
            });
        },
        {
            root: main,
            rootMargin: ROOT_MARGIN,
            threshold: OBSERVER_THRESHOLD,
        }
    );

    observeAosElements(main);

    mutationObserver = new MutationObserver((mutations) => {
        for (const m of mutations) {
            if (m.addedNodes && m.addedNodes.length) {
                setTimeout(() => observeAosElements(main), 20);
                break;
            }
        }
    });
    mutationObserver.observe(main, { childList: true, subtree: true });

    scrollHandler = () => {
        if (containerObserver && containerObserver.takeRecords) {
            containerObserver.takeRecords();
        }
    };
    main.addEventListener("scroll", scrollHandler, { passive: true });
});

onBeforeUnmount(() => {
    if (containerObserver) {
        containerObserver.disconnect();
        containerObserver = null;
    }
    if (mutationObserver) {
        mutationObserver.disconnect();
        mutationObserver = null;
    }
    const main = document.querySelector("main");
    if (main && scrollHandler)
        main.removeEventListener("scroll", scrollHandler);
});
</script>
