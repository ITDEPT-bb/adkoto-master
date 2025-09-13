<template>
    <section class="py-16 px-6 bg-blue-50">
        <div class="max-w-7xl mx-auto text-center">
            <!-- Heading -->
            <h1
                class="text-3xl md:text-5xl font-extrabold text-brand-blue mb-4"
            >
                TESTIMONIALS
            </h1>
            <p
                class="text-gray-700 text-base md:text-lg mb-10 max-w-3xl mx-auto"
            >
                Using Adkoto has been a smooth experience — the platform is easy
                to navigate, reliable, and helps me connect with the right
                opportunities.
            </p>

            <!-- MAIN CARD -->
            <div
                class="relative bg-gradient-to-t from-brand-blue to-sky-300 rounded-2xl max-w-5xl mx-auto flex flex-col md:flex-row items-start md:items-center gap-6 md:gap-10 p-6 md:p-8 text-white shadow-lg mb-10"
            >
                <!-- Floating quote -->
                <div
                    class="absolute -top-5 left-6 title-webkit-wide text-brand-green text-6xl"
                >
                    ❝
                </div>

                <!-- Left -->
                <div class="flex-1 text-left">
                    <h3 class="text-2xl md:text-3xl font-bold mb-3">
                        {{ mainTestimonial.title }}
                    </h3>

                    <div class="flex mb-4">
                        <span
                            v-for="i in 5"
                            :key="i"
                            class="text-yellow-300 text-xl md:text-2xl"
                        >
                            {{ i <= mainTestimonial.rating ? "★" : "☆" }}
                        </span>
                    </div>

                    <p
                        class="bg-white text-brand-blue rounded-lg p-5 md:p-6 max-w-2xl text-lg md:text-xl leading-relaxed"
                    >
                        {{ mainTestimonial.text }}
                    </p>
                </div>

                <!-- Right -->
                <div class="flex flex-col items-center">
                    <div
                        class="w-24 h-24 md:w-28 md:h-28 rounded-full border-4 border-white overflow-hidden"
                    >
                        <img
                            :src="mainTestimonial.avatar"
                            alt="avatar"
                            class="w-full h-full object-cover"
                        />
                    </div>
                    <p class="font-semibold mt-3 text-base md:text-lg">
                        {{ mainTestimonial.user }}
                    </p>
                    <p class="text-sm md:text-base text-white/80">
                        {{ mainTestimonial.role }}
                    </p>
                </div>
            </div>

            <div class="relative max-w-5xl mx-auto">
                <!-- Left arrow -->
                <button
                    ref="prevEl"
                    class="absolute -left-6 sm:-left-20 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-brand-green text-white flex items-center justify-center shadow"
                >
                    ‹
                </button>

                <Swiper
                    ref="thumbSwiper"
                    :modules="[Navigation]"
                    :loop="true"
                    :slides-per-view="1"
                    :space-between="16"
                    :navigation="{ prevEl: prevEl, nextEl: nextEl }"
                    :breakpoints="{
                        640: { slidesPerView: 1, spaceBetween: 16 },
                        768: { slidesPerView: 2, spaceBetween: 20 },
                        1024: { slidesPerView: 3, spaceBetween: 24 },
                    }"
                    class="px-8"
                >
                    <SwiperSlide
                        v-for="t in testimonials"
                        :key="t.id"
                        @click="setMain(t)"
                        class="bg-white rounded-xl p-5 shadow cursor-pointer hover:shadow-lg transition flex flex-col h-full"
                    >
                        <h4 class="font-bold text-lg mb-2 text-gray-800">
                            {{ t.title }}
                        </h4>

                        <div class="flex mb-2">
                            <span
                                v-for="i in 5"
                                :key="i"
                                class="text-yellow-400 text-sm"
                            >
                                {{ i <= t.rating ? "★" : "☆" }}
                            </span>
                        </div>

                        <p class="text-sm text-brand-blue mb-4 line-clamp-2">
                            "{{ t.text }}"
                        </p>

                        <div class="flex items-center gap-2 mt-auto">
                            <img
                                :src="t.avatar"
                                class="w-10 h-10 rounded-full object-cover"
                            />
                            <div>
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ t.user }}
                                </p>
                                <p class="text-xs text-brand-blue">
                                    {{ t.role }}
                                </p>
                            </div>
                        </div>
                    </SwiperSlide>
                </Swiper>

                <!-- Right arrow -->
                <button
                    ref="nextEl"
                    class="absolute -right-6 sm:-right-20 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-brand-green text-white flex items-center justify-center shadow"
                >
                    ›
                </button>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";

const testimonials = ref([
    {
        id: 1,
        title: "Excellent!",
        rating: 4,
        text: "Adkoto made it so easy for me to explore different opportunities in one platform. Very user-friendly and reliable!",
        user: "Maria Dela Cruz",
        role: "Adkoto User",
        avatar: "https://placehold.co/400",
    },
    {
        id: 2,
        title: "Great Project!",
        rating: 5,
        text: "Great platform! Adkoto helped me save time and effort while finding what I needed.",
        user: "Juan Dela Cruz",
        role: "Project Manager",
        avatar: "https://placehold.co/400",
    },
    {
        id: 3,
        title: "Highly Recommended!",
        rating: 5,
        text: "A wonderful experience overall. Very professional and reliable service!",
        user: "Ana Santos",
        role: "Business Owner",
        avatar: "https://placehold.co/400",
    },
    {
        id: 4,
        title: "Very Helpful",
        rating: 4,
        text: "This platform connected me with the right opportunities faster than expected!",
        user: "Carlos Reyes",
        role: "Freelancer",
        avatar: "https://placehold.co/400",
    },
    {
        id: 5,
        title: "Smooth Process",
        rating: 5,
        text: "Everything works smoothly, from browsing to applying. Highly efficient.",
        user: "Liza Gomez",
        role: "Designer",
        avatar: "https://placehold.co/400",
    },
    {
        id: 6,
        title: "Love It!",
        rating: 5,
        text: "The best decision I made this year. Highly recommended for everyone.",
        user: "Mark Rivera",
        role: "Engineer",
        avatar: "https://placehold.co/400",
    },
]);

const mainTestimonial = ref(testimonials.value[0]);

function setMain(t) {
    mainTestimonial.value = t;
}

const thumbSwiper = ref(null);
const prevEl = ref(null);
const nextEl = ref(null);

onMounted(() => {
    if (thumbSwiper.value?.swiper) {
        thumbSwiper.value.swiper.params.navigation.prevEl = prevEl.value;
        thumbSwiper.value.swiper.params.navigation.nextEl = nextEl.value;
        thumbSwiper.value.swiper.navigation.init();
        thumbSwiper.value.swiper.navigation.update();
    }
});
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
