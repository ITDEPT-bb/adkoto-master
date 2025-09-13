<template>
    <div class="bg-cover bg-no-repeat bg-ads rounded-lg p-6">
        <div
            v-if="featuredAds.length === 0"
            class="text-center text-white font-boldmarker p-10"
        >
            No Advertisements available at the moment.
        </div>

        <div
            v-else
            class="flex w-full mx-auto flex-wrap md:flex-nowrap justify-center items-center"
        >
            <!-- First Carousel -->
            <swiper
                :modules="[Navigation, Pagination, EffectFade, Autoplay]"
                effect="fade"
                :loop="true"
                :autoplay="{ delay: 5000, disableOnInteraction: false }"
                :navigation="true"
                :pagination="{ clickable: true }"
                class="w-full max-w-3xl h-48"
            >
                <swiper-slide v-for="ad in firstCarouselAds" :key="ad.id">
                    <img
                        :src="ad.attachments[0]?.image_path"
                        alt="Featured Ad"
                        class="w-full h-full object-contain rounded-lg"
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
                class="w-full max-w-3xl h-48"
            >
                <swiper-slide v-for="ad in secondCarouselAds" :key="ad.id">
                    <img
                        :src="ad.attachments[0]?.image_path"
                        alt="Featured Ad"
                        class="w-full h-full object-contain rounded-lg"
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
