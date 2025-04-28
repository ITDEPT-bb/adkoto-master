<template>
    <Link
        :href="
            route('kalakalkoto.show', {
                id: item.id,
            })
        "
    >
        <div
            class="flex flex-col items-start p-4 max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out dark:border-gray-700 border border-gray-200"
        >
            <div class="w-full h-40 overflow-hidden rounded-t-lg">
                <img
                    :src="
                        item.attachments[0]?.image_path ||
                        'https://via.placeholder.com/100'
                    "
                    :alt="item.name"
                    class="w-full h-full object-cover rounded-t-lg"
                />
            </div>

            <div class="p-2 flex flex-col space-y-4">
                <div class="flex flex-col w-full space-y-2">
                    <h2
                        class="text-xl font-semibold text-gray-900 dark:text-gray-100 line-clamp-1"
                    >
                        {{ item.name }}
                    </h2>
                    <p
                        class="text-lg font-bold text-gray-900 dark:text-gray-200"
                    >
                        {{ formatPrice(item.price) }}
                    </p>
                    <p
                        class="text-base text-gray-700 dark:text-gray-300 line-clamp-1"
                    >
                        {{ item.description }}
                    </p>
                </div>
                <div class="flex items-center text-gray-700 dark:text-gray-300">
                    <MapPinIcon
                        class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400"
                    />
                    <p
                        class="text-sm font-semibold text-gray-600 dark:text-gray-400"
                    >
                        {{ item.location }}
                    </p>
                </div>
                <div class="flex items-center text-gray-700 dark:text-gray-300">
                    <UserIcon
                        class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400"
                    />
                    <p
                        class="text-sm font-semibold text-gray-600 dark:text-gray-400"
                    >
                        {{ item.user.name }}
                    </p>
                </div>
            </div>
        </div>
    </Link>
</template>

<script setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";
import UserIcon from "@/Components/Icons/UserIcon.vue";
import MapPinIcon from "@/Components/Icons/MapPinIcon.vue";

const props = defineProps({
    item: Object,
});

const formatPrice = (price) => {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
        minimumFractionDigits: 2,
    }).format(price);
};
</script>
