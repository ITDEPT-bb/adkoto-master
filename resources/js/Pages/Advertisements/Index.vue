<template>
    <Head title="Kalakalkoto" />

    <KalakalLayout>
        <div class="max-w-7xl mx-auto h-full overflow-y-auto p-4 bg-gray-200">
            <div class="grid grid-cols-12 gap-6">
                <!-- Left Column: Categories -->
                <aside class="col-span-3 bg-white p-4 shadow rounded-lg">
                    <!-- Categories with Subcategories -->
                    <section>
                        <h1 class="text-2xl font-bold mb-4">Ad Categories</h1>
                        <div class="space-y-4">
                            <div
                                v-for="category in categories"
                                :key="category.id"
                                class="border-b pb-2"
                            >
                                <h2 class="text-xl font-semibold text-red-600">
                                    {{ category.name }} ({{
                                        category.sub_categories.length
                                    }})
                                </h2>
                                <ul class="pl-4 space-y-1">
                                    <li
                                        v-for="subCategory in category.sub_categories"
                                        :key="subCategory.id"
                                        class="text-gray-700 text-sm"
                                    >
                                        {{ subCategory.name }} (0)
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>
                </aside>

                <!-- Middle Column: Main Content (Listings) -->
                <main class="col-span-6 bg-white p-4 shadow rounded-lg">
                    <section>
                        <div class="flex space-x-4 mb-4">
                            <button class="text-lg font-semibold">
                                New Listings
                            </button>
                            <button class="text-lg">Popular</button>
                            <button class="text-lg">Random</button>
                        </div>
                        <div
                            v-for="advertisement in advertisements"
                            :key="advertisement.id"
                            class="border-b py-4"
                        >
                            <div class="flex items-start space-x-4">
                                <img
                                    :src="
                                        advertisement.image ||
                                        'https://via.placeholder.com/100'
                                    "
                                    alt="Listing Image"
                                    class="w-20 h-20 object-cover rounded"
                                />
                                <div>
                                    <h3 class="text-lg font-semibold">
                                        {{ advertisement.title }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        {{ advertisement.description }}
                                    </p>
                                    <div
                                        class="text-sm text-gray-500 flex items-center space-x-2 mt-2"
                                    >
                                        <span>{{
                                            advertisement.category
                                        }}</span>
                                        <span>{{ advertisement.date }}</span>
                                        <span>394 total views, 1 today</span>
                                    </div>
                                </div>
                                <div class="ml-auto">
                                    <span
                                        class="bg-red-500 text-white py-1 px-2 rounded"
                                        >¥{{ advertisement.price }}</span
                                    >
                                </div>
                            </div>
                        </div>
                    </section>
                </main>

                <!-- Right Column: Sponsored Ads -->
                <aside class="col-span-3 bg-white p-4 shadow rounded-lg">
                    <h2 class="text-2xl font-bold mb-4">Sponsored Ads</h2>
                    <div class="space-y-4">
                        <div
                            v-for="ad in sponsoredAds"
                            :key="ad.id"
                            class="flex items-center space-x-4"
                        >
                            <img
                                :src="
                                    ad.image ||
                                    'https://via.placeholder.com/100'
                                "
                                alt="Sponsored Ad Image"
                                class="w-16 h-16 object-cover rounded"
                            />
                            <div>
                                <h3 class="text-lg font-semibold">
                                    {{ ad.title }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{ ad.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </KalakalLayout>

    <UpdateProfileReminder />
</template>

<script setup>
import { ref } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import KalakalLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

const { props } = usePage();
const advertisements = ref(props.advertisements);
const categories = ref(props.categories);
const sponsoredAds = ref(props.sponsoredAds);

console.log(categories.value);
</script>
