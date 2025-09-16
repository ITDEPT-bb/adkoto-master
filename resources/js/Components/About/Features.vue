<script setup>
import { Link } from "@inertiajs/vue3";

const features = [
    {
        icon: "/assets/icon/connect_with_real_people.png",
        title: "Connect with real people",
    },
    {
        icon: "/assets/icon/advertise_your_brands.png",
        title: "Advertise your brands",
    },
    {
        icon: "/assets/icon/sell_your_products.png",
        title: "Sell your products",
    },
    {
        icon: "/assets/icon/no_ai.png",
        title: "No A.I. (Artificial Intelligence)",
    },
];
</script>

<template>
    <section
        class="max-w-7xl mx-auto px-6 lg:py-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
    >
        <div
            v-for="feature in features"
            :key="feature.title"
            class="p-6 bg-white border-4 border-sky-400 shadow rounded-xl text-center hover:scale-105 transition-transform"
        >
            <img :src="feature.icon" class="mx-auto h-16 mb-4" />
            <h4 class="font-bold text-lg">{{ feature.title }}</h4>
            <Link
                :href="route('dashboard')"
                class="mt-4 inline-block px-4 py-2 border-2 border-brand-blue rounded-md font-semibold bg-gray-100 text-brand-blue hover:bg-blue-100"
            >
                Learn more...
            </Link>
        </div>
    </section>
</template>
