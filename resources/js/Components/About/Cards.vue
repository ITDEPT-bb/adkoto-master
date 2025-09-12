<template>
    <section class="bg-white py-12">
        <div
            class="max-w-7xl mx-auto px-4 grid gap-6 sm:grid-cols-2 lg:grid-cols-4"
        >
            <!-- Card -->
            <div
                v-for="(card, index) in cards"
                :key="index"
                class="bg-cyan-100 rounded-2xl p-6 flex flex-col items-center justify-center h-72 sm:h-80 lg:h-96 group relative overflow-hidden transition-transform duration-300 hover:scale-105"
            >
                <div
                    class="w-28 h-28 sm:w-32 sm:h-32 lg:w-52 lg:h-52 p-6 bg-white rounded flex items-center justify-center mb-4"
                >
                    <img
                        :src="card.icon"
                        :alt="card.title"
                        class="w-full h-full object-contain"
                    />
                </div>
                <h3
                    class="text-brand-blue font-boldmarker text-lg sm:text-2xl mb-2 text-center uppercase"
                >
                    {{ card.title }}
                </h3>
                <!-- Description overlay -->
                <div
                    class="absolute text-white inset-0 bg-white/95 bg-gradient-to-t from-brand-blue to-sky-300 p-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center text-center text-sm sm:text-base"
                >
                    <h4 class="font-boldmarker text-xl sm:text-2xl mb-2">
                        {{ card.title }}
                    </h4>
                    <p class="text-base sm:text-lg">
                        {{ card.description }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const cards = [
    {
        title: "Your Tribe Online",
        icon: "/assets/icon/your_tribe.png",
        description:
            "A digital home where you can find and connect with like-minded people.",
    },
    {
        title: "Connect with Your Community",
        icon: "/assets/icon/connect_with.png",
        description:
            "Encourages users to engage and build relationships within their own circles or groups.",
    },
    {
        title: "Building Tribes Together",
        icon: "/assets/icon/building_tribes.png",
        description:
            "Focuses on collaboration and creating strong groups united by shared interests or goals.",
    },
    {
        title: "Where Communities Grow",
        icon: "/assets/icon/communities.png",
        description:
            "Suggests that the platform helps communities expand, strengthen, and thrive.",
    },
];
</script>
