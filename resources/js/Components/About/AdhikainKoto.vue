<template>
    <section
        class="min-h-screen bg-adhikainKoto bg-cover bg-no-repeat py-10 px-6"
    >
        <div class="mx-auto text-center">
            <!-- Heading -->
            <h1
                class="uppercase font-boldmarker text-white text-5xl md:text-7xl tracking-wide mb-4"
            >
                Adhikain Koto
            </h1>
            <h6 class="text-xl md:text-2xl text-white font-semibold mb-12">
                Your all-in-one platform where real people connect, trade, and
                grow.
            </h6>

            <!-- Row 1 -->
            <Swiper
                :modules="[Autoplay]"
                :slides-per-view="1"
                :space-between="10"
                :loop="true"
                :allow-touch-move="false"
                :autoplay="{
                    delay: 0,
                    disableOnInteraction: false,
                    reverseDirection: false,
                }"
                :speed="6000"
                :breakpoints="{
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 40,
                    },
                }"
                class="mb-8"
            >
                <SwiperSlide
                    v-for="(image, index) in row1Images"
                    :key="'row1-' + index"
                >
                    <img
                        :src="image"
                        alt="Row 1 Image"
                        class="w-full h-48 sm:h-60 md:h-72 object-contain rounded-xl"
                    />
                </SwiperSlide>
            </Swiper>

            <!-- Row 2 -->
            <Swiper
                :modules="[Autoplay]"
                :slides-per-view="1"
                :space-between="10"
                :loop="true"
                :allow-touch-move="false"
                :autoplay="{
                    delay: 0,
                    disableOnInteraction: false,
                    reverseDirection: true,
                }"
                :speed="6000"
                :breakpoints="{
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 40,
                    },
                }"
            >
                <SwiperSlide
                    v-for="(image, index) in row2Images"
                    :key="'row2-' + index"
                >
                    <img
                        :src="image"
                        alt="Row 2 Image"
                        class="w-full h-48 sm:h-60 md:h-72 object-contain rounded-xl"
                    />
                </SwiperSlide>
            </Swiper>
        </div>
    </section>
</template>

<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay } from "swiper/modules";
import "swiper/css";

const row1Images = [
    "/assets/picture/pic1.png",
    "/assets/picture/pic2.png",
    "/assets/picture/pic3.png",
    "/assets/picture/pic4.png",
    "/assets/picture/pic5.png",
];

const row2Images = [
    "/assets/picture/pic5.png",
    "/assets/picture/pic4.png",
    "/assets/picture/pic3.png",
    "/assets/picture/pic2.png",
    "/assets/picture/pic1.png",
];
</script>
