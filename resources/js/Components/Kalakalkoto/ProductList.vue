<template>
    <div class="p-4 rounded-lg dark:border-gray-700">
        <div class="grid lg:grid-cols-4 gap-4 mb-4">
            <div
                v-for="item in items"
                :key="item.id"
                class="flex flex-col items-start p-4 max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg"
            >
                <div class="w-full h-40 overflow-hidden rounded-t-lg">
                    <img
                        :src="`/storage/${
                            item.images[0]?.path || 'placeholder.png'
                        }`"
                        :alt="item.name"
                        class="w-full h-full object-cover rounded-t-lg"
                    />
                </div>
            </div>

            <!-- <div class="p-4 flex flex-col items-start space-y-2">
                <div class="flex flex-col justify-between w-full">
                    <h2
                        class="text-lg font-semibold text-gray-800 dark:text-gray-200"
                    >
                        {{ item.title }}
                    </h2>
                    <p
                        class="text-lg font-bold text-gray-900 dark:text-gray-100"
                    >
                        {{ formatPrice(item.price) }}
                    </p>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Location: {{ item.location }}
                </p>
            </div> -->
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const { props } = usePage();
const items = ref(props.items);

const formatPrice = (price) => {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
        minimumFractionDigits: 2,
    }).format(price);
};
</script>
