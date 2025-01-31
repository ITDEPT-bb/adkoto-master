<template>
	<aside class="bg-white p-4 shadow my-2 rounded-lg">
		<h2 class="text-2xl font-bold mb-4">Sponsored Ads</h2>
		<div class="space-y-4">
			<div class="flex flex-col items-center space-y-6 py-4">
				<!-- First Slider -->
				<swiper
					:modules="[Navigation, Pagination, EffectFade, Autoplay]"
					effect="fade"
					:space-between="20"
					:slides-per-view="1"
					:loop="true"
					:autoplay="{ delay: 5000, disableOnInteraction: false }"
					:navigation="true"
					:pagination="{ clickable: true }"
					class="w-full">
					<!-- Loop through first set of ads -->
					<swiper-slide
						v-for="(ad, index) in firstSliderAds"
						:key="index"
						class="flex flex-col gap-4 justify-center">
						<img
							:src="ad.attachments[0]?.image_path || 'https://via.placeholder.com/150'"
							:alt="ad.title"
							class="w-96 h-auto object-cover" />
					</swiper-slide>
				</swiper>

				<!-- Second Slider -->
				<swiper
					:modules="[Navigation, Pagination, EffectFade, Autoplay]"
					effect="fade"
					:space-between="20"
					:slides-per-view="1"
					:loop="true"
					:autoplay="{ delay: 5000, disableOnInteraction: false }"
					:navigation="true"
					:pagination="{ clickable: true }"
					class="w-full">
					<!-- Loop through second set of ads -->
					<swiper-slide
						v-for="(ad, index) in secondSliderAds"
						:key="index"
						class="flex flex-col gap-4 justify-center">
						<img
							:src="ad.attachments[0]?.image_path || 'https://via.placeholder.com/150'"
							:alt="ad.title"
							class="w-96 h-auto object-cover" />
					</swiper-slide>
				</swiper>
			</div>

			<!-- Message if no sponsored ads available -->
			<div
				v-if="!sponsoredAds.length"
				class="text-center text-gray-500">
				No sponsored ads available.
			</div>
		</div>
	</aside>
</template>

<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import { EffectFade, Autoplay, Navigation, Pagination } from "swiper/modules";
import "swiper/css";
// import "swiper/css/navigation";
import "swiper/css/autoplay";
import "swiper/css/effect-fade";
import { ref, computed } from "vue";

const props = defineProps({
	sponsoredAds: {
		type: Array,
		required: true,
	},
});

const sponsoredAds = ref(props.sponsoredAds);

const firstSliderAds = computed(() => sponsoredAds.value.filter((ad, index) => index % 2 === 0));
const secondSliderAds = computed(() => sponsoredAds.value.filter((ad, index) => index % 2 !== 0));
</script>
