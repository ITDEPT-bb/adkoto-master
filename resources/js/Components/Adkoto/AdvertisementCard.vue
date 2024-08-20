<template>
    <div
        class="w-full mx-auto p-4 bg-white border border-gray-200 rounded-lg shadow-md"
    >
        <!-- Image Carousel -->
        <div class="relative">
            <div class="overflow-hidden rounded-t-lg">
                <div
                    class="flex"
                    :style="{
                        transform: `translateX(-${currentIndex * 100}%)`,
                        transition: 'transform 0.5s ease',
                    }"
                >
                    <img
                        v-for="(attachment, index) in advertisement.attachments"
                        :key="index"
                        :src="attachment.image_path"
                        alt="Advertisement Image"
                        class="w-full h-full object-contain"
                    />
                </div>
            </div>
            <button
                @click="prevImage"
                class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-white text-blue-500 font-bold p-2 rounded-full"
            >
                &lt;
            </button>
            <button
                @click="nextImage"
                class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-white text-blue-500 font-bold p-2 rounded-full"
            >
                &gt;
            </button>
        </div>

        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                {{ advertisement.title }}
            </h1>
            <p class="text-gray-600 mb-4">{{ advertisement.description }}</p>
            <div class="flex items-center gap-5">
                <span class="flex items-center text-gray-500">
                    <UserIcon class="w-5 h-5 mr-2" />
                    {{ advertisement.user.name }}
                </span>
                <span class="flex items-center text-gray-500">
                    <ClockIcon class="w-5 h-5 mr-2" />
                    {{ dayjs(advertisement.updated_at).fromNow() }}
                </span>
                <span class="flex items-center text-gray-500">
                    <SettingsIcon class="w-5 h-5 mr-2" />
                    {{ advertisement.category.name }}
                </span>
                <span
                    class="bg-blue-500 flex items-center text-white py-1 px-2 rounded text-xs"
                >
                    <TagIcon class="w-4 h-4 mr-1" />
                    {{ advertisement.price }}
                </span>
            </div>
        </div>

        <!-- Manage Button (Edit/Delete) -->
        <div v-if="advertisement.user.id === authUser.id" class="flex-2">
            <a
                :href="
                    route('adkoto.edit', {
                        id: advertisement.id,
                    })
                "
                class="bg-yellow-500 text-white py-1 px-2 rounded text-xs mr-2"
            >
                Edit
            </a>
            <button
                @click="deleteAd(advertisement.id)"
                class="bg-red-500 text-white py-1 px-2 rounded text-xs"
            >
                Delete
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import UserIcon from "@/Components/Icons/UserIcon.vue";
import ClockIcon from "@/Components/Icons/ClockIcon.vue";
import SettingsIcon from "@/Components/Icons/SettingsIcon.vue";
import TagIcon from "../Icons/TagIcon.vue";

dayjs.extend(relativeTime);

const { props } = usePage();
const advertisement = ref(props.advertisement);

// Get the authenticated user
const authUser = usePage().props.auth.user;

const currentIndex = ref(0);

const nextImage = () => {
    if (currentIndex.value < advertisement.value.attachments.length - 1) {
        currentIndex.value++;
    } else {
        currentIndex.value = 0;
    }
};

const prevImage = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    } else {
        currentIndex.value = advertisement.value.attachments.length - 1;
    }
};
</script>

<style scoped>
/* Additional styles if needed */
</style>
