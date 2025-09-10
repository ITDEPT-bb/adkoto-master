<template>
    <div class="w-full bg-white dark:bg-slate-950 shadow rounded-lg mb-2">
        <!-- Mobile Dropdown for Tabs -->
        <div class="sm:hidden">
            <label for="tabs" class="sr-only">Section</label>
            <select
                id="tabs"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                @change="changeRoute($event)"
            >
                <option
                    value="kalakalkoto"
                    :selected="route().current('kalakalkoto')"
                >
                    Kalakalkoto
                </option>
                <option value="auction" :selected="isAuctionActive">
                    Auction
                </option>
            </select>
        </div>

        <!-- Desktop Tabs -->
        <ul
            class="hidden text-sm font-semibold text-center text-gray-500 rounded-lg shadow sm:flex dark:divide-gray-700 dark:text-gray-400"
        >
            <!-- Auction -->
            <li
                :class="[
                    isAuctionActive ? 'order-1' : 'order-2',
                    'me-2 w-full flex justify-center items-center align-middle',
                ]"
            >
                <Link
                    :href="route('auction')"
                    class="inline-flex items-center justify-center border-transparent rounded-t-lg group transition-all duration-200"
                    :class="{
                        // 👇 Bigger & highlighted when active
                        'p-5 text-lg font-bold text-blue-600 border-b-4 border-blue-600':
                            isAuctionActive,
                        // 👇 Normal style when inactive
                        'p-4 text-gray-500 border-b-2 hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300':
                            !isAuctionActive,
                    }"
                >
                    <UserGroupIcon
                        :class="[
                            isAuctionActive
                                ? 'w-7 h-7 text-blue-600 dark:text-blue-400'
                                : 'w-6 h-6 text-gray-400 dark:text-gray-500',
                            'me-1 transition-all duration-200',
                        ]"
                    />
                    <span class="text-xl">Auction</span>
                </Link>
            </li>

            <!-- Kalakalkoto -->
            <li
                :class="[
                    isAuctionActive ? 'order-2' : 'order-1',
                    'me-2 w-full flex justify-center items-center align-middle',
                ]"
            >
                <Link
                    :href="route('kalakalkoto')"
                    class="inline-flex items-center justify-center border-transparent rounded-t-lg group transition-all duration-200"
                    :class="{
                        'p-5 text-lg font-bold text-blue-600 border-b-4 border-blue-600':
                            route().current('kalakalkoto'),
                        'p-4 text-gray-500 border-b-2 hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300':
                            !route().current('kalakalkoto'),
                    }"
                >
                    <ShoppingBagIcon
                        :class="[
                            route().current('kalakalkoto')
                                ? 'w-7 h-7 text-blue-600 dark:text-blue-400'
                                : 'w-6 h-6 text-gray-400 dark:text-gray-500',
                            'me-1 transition-all duration-200',
                        ]"
                    />
                    <span>Kalakalkoto</span>
                </Link>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { router } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import ShoppingBagIcon from "./ShoppingBagIcon.vue";
import UserGroupIcon from "./UserGroupIcon.vue";

const changeRoute = (event) => {
    const selectedRoute = event.target.value;
    router.get(route(selectedRoute));
};

// ✅ Auction is active if either auction or auction.watchStream
const isAuctionActive = computed(
    () => route().current("auction") || route().current("auction.watchStream")
);
</script>
