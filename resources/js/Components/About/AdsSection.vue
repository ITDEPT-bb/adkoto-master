<template>
    <div class="bg-cover bg-no-repeat bg-ads rounded-lg">
        <!-- Display message if no featured ads are found -->
        <div
            v-if="featuredAds.length === 0"
            class="text-center text-white font-boldmarker p-10"
        >
            No Advertisements available at the moment.
        </div>

        <!-- If there are featured ads, display them in carousels -->
        <div
            v-else
            class="flex w-full px-2 flex-wrap md:flex-nowrap justify-center items-center gap-4"
        >
            <!-- First Carousel -->
            <swiper
                :modules="[Navigation, Pagination, EffectFade, Autoplay]"
                effect="fade"
                :loop="true"
                :autoplay="{ delay: 5000, disableOnInteraction: false }"
                :navigation="true"
                :pagination="{ clickable: true }"
                class="w-full sm:w-1/2 lg:w-1/2 h-auto"
            >
                <swiper-slide v-for="ad in firstCarouselAds" :key="ad.id">
                    <img
                        :src="ad.attachments[0]?.image_path"
                        alt="Featured Ad"
                        class="w-full h-auto object-cover rounded"
                    />
                </swiper-slide>
            </swiper>

            <!-- Second Carousel -->
            <swiper
                :modules="[Navigation, Pagination, EffectFade, Autoplay]"
                effect="fade"
                :loop="true"
                :autoplay="{ delay: 5000, disableOnInteraction: false }"
                :navigation="true"
                :pagination="{ clickable: true }"
                class="w-full sm:w-1/2 lg:w-1/2 h-auto"
            >
                <swiper-slide v-for="ad in secondCarouselAds" :key="ad.id">
                    <img
                        :src="ad.attachments[0]?.image_path"
                        alt="Featured Ad"
                        class="w-full h-auto object-cover rounded"
                    />
                </swiper-slide>
            </swiper>
        </div>
    </div>
</template>

<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import { EffectFade, Autoplay, Navigation, Pagination } from "swiper/modules";
import "swiper/css";
import "swiper/css/effect-fade";
import { ref, computed, onMounted } from "vue";

const props = defineProps({
    featuredAds: {
        type: Array,
        required: true,
    },
});

const featuredAds = ref(props.featuredAds);

const firstCarouselAds = computed(() =>
    featuredAds.value.filter((ad, index) => index % 2 === 0)
);
const secondCarouselAds = computed(() =>
    featuredAds.value.filter((ad, index) => index % 2 !== 0)
);
</script>
