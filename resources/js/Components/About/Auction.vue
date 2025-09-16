<template>
    <section class="py-12 px-6 bg-white">
        <div class="max-w-7xl mx-auto text-center">
            <!-- Heading -->
            <h1
                class="uppercase font-boldmarker text-brand-blue text-5xl md:text-7xl tracking-wide mb-4"
            >
                Auction
            </h1>
            <p class="text-lg md:text-xl text-gray-700 mb-10">
                Join our auction and explore a wide variety of insurance options
                tailored to your needs
            </p>

            <!-- Swiper -->
            <Swiper
                :modules="[Pagination, Autoplay]"
                :slides-per-view="1"
                :space-between="20"
                :loop="true"
                :autoplay="{ delay: 3000, disableOnInteraction: false }"
                :pagination="{ clickable: true }"
                :breakpoints="{
                    640: { slidesPerView: 1 },
                    768: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 },
                }"
                class="auction-swiper"
            >
                <SwiperSlide v-for="auction in auctions.data" :key="auction.id">
                    <div
                        class="border-2 border-brand-blue rounded-xl overflow-hidden p-4 flex flex-col items-center shadow-md"
                    >
                        <img
                            :src="
                                auction.attachments[0]?.image_path ??
                                '/assets/picture/auction_pic1.png'
                            "
                            :alt="auction.title"
                            class="h-48 w-auto object-contain mb-4"
                        />

                        <h3 class="text-lg font-semibold text-gray-800 mb-3">
                            {{ auction.title }}
                        </h3>

                        <Link
                            :href="route('auction')"
                            class="px-4 py-2 text-sm font-medium rounded-md text-white bg-brand-blue hover:bg-brand-blue/90 transition"
                        >
                            Learn More
                        </Link>
                    </div>
                </SwiperSlide>
            </Swiper>
        </div>
    </section>
</template>

<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import { Pagination, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/pagination";
import { Link } from "@inertiajs/vue3";

defineProps({
    auctions: {
        type: Object,
        required: true,
    },
});
</script>

<style scoped>
.auction-swiper {
    padding-bottom: 2.5rem;
}
:deep(.swiper-pagination-bullet) {
    background: #2563eb;
    opacity: 0.5;
}
:deep(.swiper-pagination-bullet-active) {
    background: #2563eb;
    opacity: 1;
}
</style>
