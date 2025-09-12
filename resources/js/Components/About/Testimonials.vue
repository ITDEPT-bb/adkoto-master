<template>
    <section
        class="min-h-screen bg-testimonials bg-cover bg-no-repeat py-12 px-6"
    >
        <div class="max-w-7xl mx-auto text-center">
            <!-- Heading -->
            <h1
                class="uppercase font-boldmarker text-brand-blue text-5xl md:text-7xl tracking-wide mb-4"
            >
                Testimonials
            </h1>
            <h6 class="text-lg md:text-xl text-gray-700 mb-10">
                Using Adkoto has been a smooth experience the platform is easy
                to navigate, reliable, and helps me connect with the right
                opportunities
            </h6>

            <!-- Active Testimonial -->
            <div
                class="flex items-center justify-between bg-gradient-to-r from-blue-400 to-blue-500 text-white p-6 rounded-2xl shadow-lg mb-10"
            >
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-4xl text-green-400">“</span>
                        <h3 class="text-2xl font-bold">
                            {{ testimonials[activeIndex].title }}
                        </h3>
                    </div>

                    <div class="flex mb-2">
                        <span
                            v-for="i in 5"
                            :key="i"
                            class="text-yellow-300 text-xl"
                        >
                            {{
                                i <= testimonials[activeIndex].rating
                                    ? "★"
                                    : "☆"
                            }}
                        </span>
                    </div>

                    <div class="bg-white/20 rounded-xl p-4 max-w-xl">
                        <p class="text-white">
                            {{ testimonials[activeIndex].text }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col items-center ml-6">
                    <img
                        :src="testimonials[activeIndex].avatar"
                        :alt="testimonials[activeIndex].user"
                        class="w-24 h-24 rounded-full border-4 border-white mb-2 object-cover"
                    />
                    <p class="font-semibold">
                        {{ testimonials[activeIndex].user }}
                    </p>
                    <p class="text-sm opacity-80">
                        {{ testimonials[activeIndex].role }}
                    </p>
                </div>
            </div>

            <!-- Thumbnails -->
            <div class="flex items-center justify-center gap-4">
                <button
                    @click="prev"
                    class="bg-green-500 text-white rounded-full p-3 shadow hover:bg-green-600"
                >
                    ‹
                </button>

                <div class="flex gap-4 overflow-x-auto max-w-3xl">
                    <div
                        v-for="(t, index) in testimonials"
                        :key="t.id"
                        @click="setActive(index)"
                        class="min-w-[250px] bg-white rounded-xl p-4 shadow cursor-pointer border"
                        :class="{ 'border-blue-500': index === activeIndex }"
                    >
                        <h4 class="font-bold text-lg mb-1">{{ t.title }}</h4>
                        <div class="flex mb-1">
                            <span
                                v-for="i in 5"
                                :key="i"
                                class="text-yellow-400 text-sm"
                            >
                                {{ i <= t.rating ? "★" : "☆" }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">
                            “{{ t.text.slice(0, 70) }}...”
                        </p>
                        <div class="flex items-center gap-2 mt-auto">
                            <img
                                :src="t.avatar"
                                class="w-8 h-8 rounded-full object-cover"
                            />
                            <div>
                                <p class="text-sm font-semibold">
                                    {{ t.user }}
                                </p>
                                <p class="text-xs text-blue-500">
                                    {{ t.role }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <button
                    @click="next"
                    class="bg-green-500 text-white rounded-full p-3 shadow hover:bg-green-600"
                >
                    ›
                </button>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from "vue";

const testimonials = ref([
    {
        id: 1,
        title: "Excellent!",
        rating: 4,
        text: "Adkoto made it so easy for me to explore different opportunities in one platform very user-friendly and reliable!",
        user: "Maria Dela Cruz",
        role: "Adkoto user",
        avatar: "/images/maria.jpg",
    },
    {
        id: 2,
        title: "Great Project!",
        rating: 5,
        text: "Great platform! Adkoto helped me save time and effort while finding what I needed.",
        user: "Maria Dela Cruz",
        role: "Adkoto user",
        avatar: "/images/maria.jpg",
    },
    {
        id: 3,
        title: "Highly Recommended!",
        rating: 5,
        text: "Adkoto made it so easy for me to explore different opportunities in one platform very user-friendly and reliable!",
        user: "Maria Dela Cruz",
        role: "Adkoto user",
        avatar: "/images/maria.jpg",
    },
]);

const activeIndex = ref(0);

const setActive = (index) => {
    activeIndex.value = index;
};

const prev = () => {
    activeIndex.value =
        (activeIndex.value - 1 + testimonials.value.length) %
        testimonials.value.length;
};

const next = () => {
    activeIndex.value = (activeIndex.value + 1) % testimonials.value.length;
};
</script>
