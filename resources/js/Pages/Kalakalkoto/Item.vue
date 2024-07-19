<script setup>
import { ref, computed } from "vue";
import { usePage, Head, Link } from "@inertiajs/vue3";
import KalakalLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

// const { props } = usePage();
// const item = ref(props.item);
const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const currentImageIndex = ref(0);

const imageCount = computed(() => props.item.images.length);

const currentImage = computed(
    () =>
        props.item.images[currentImageIndex.value] || {
            url: "https://via.placeholder.com/150",
        }
);

const nextImage = () => {
    currentImageIndex.value = (currentImageIndex.value + 1) % imageCount.value;
};

const prevImage = () => {
    currentImageIndex.value =
        (currentImageIndex.value - 1 + imageCount.value) % imageCount.value;
};
</script>

<template>
    <Head title="Item Detail" />

    <KalakalLayout>
        <div class="max-w-7xl overflow-y-auto h-full mx-auto p-4">
            <div
                class="bg-white dark:bg-slate-950 rounded-lg border dark:border-slate-900 p-6 flex"
            >
                <!-- Image Carousel Section -->
                <div class="relative w-1/3">
                    <!-- Image Display -->
                    <div
                        class="w-full h-64 overflow-hidden rounded-lg shadow-lg"
                    >
                        <img
                            :src="
                                currentImage.path ||
                                'https://via.placeholder.com/150'
                            "
                            :alt="item.name"
                            class="w-full h-full object-contain"
                        />
                    </div>

                    <!-- Navigation Buttons -->
                    <button
                        @click="prevImage"
                        class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full hover:bg-gray-700"
                    >
                        ‹
                    </button>
                    <button
                        @click="nextImage"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full hover:bg-gray-700"
                    >
                        ›
                    </button>
                </div>

                <!-- Info Section -->
                <div class="w-2/3 pl-6">
                    <h3 class="text-3xl font-semibold mb-2">{{ item.name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        {{ item.description }}
                    </p>
                    <div class="flex items-center justify-between mb-4">
                        <p
                            class="text-2xl font-bold text-gray-800 dark:text-gray-200"
                        >
                            ₱{{ item.price }}
                        </p>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Category: {{ item.category.name }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Posted by: {{ item.user.name }}
                    </p>
                </div>
            </div>
        </div>
    </KalakalLayout>
    <UpdateProfileReminder />
</template>

<style scoped></style>
