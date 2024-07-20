<template>
    <button
        data-drawer-target="default-sidebar"
        data-drawer-toggle="default-sidebar"
        aria-controls="default-sidebar"
        type="button"
        class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
    >
        <span class="sr-only">Open sidebar</span>
        <svg
            class="w-6 h-6"
            aria-hidden="true"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                clip-rule="evenodd"
                fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
            ></path>
        </svg>
    </button>

    <aside
        id="default-sidebar"
        class="fixed left-0 top-0 z-40 w-64 shadow-xl h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar"
    >
        <div class="h-full px-3 py-4 overflow-y-auto bg-white dark:bg-gray-800">
            <Link
                :href="route('kalakalkoto')"
                class="flex items-center ps-5 mb-5"
                ><img :src="logo" class="h-6 me-3 sm:h-10" alt="Logo" />
            </Link>
            <ul class="space-y-2 font-medium">
                <li>
                    <Link
                        :href="route('kalakalkoto')"
                        class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group"
                    >
                        <span class="ms-3 text-sm">Home</span>
                    </Link>
                </li>
                <li>
                    <Link
                        :href="route('kalakalkoto.item.create')"
                        class="flex items-center p-2 text-gray-900 hover:bg-gray-100 transition duration-75 rounded-lg group"
                    >
                        <span class="ms-3 text-sm">List a new Item</span>
                    </Link>
                </li>
                <li>
                    <Link
                        :href="route('kalakalkoto.userItems')"
                        class="flex items-center p-2 text-gray-900 hover:bg-gray-100 transition duration-75 rounded-lg group"
                    >
                        <span class="ms-3 text-sm">My Listing</span>
                    </Link>
                </li>
            </ul>

            <!-- Separator -->
            <!-- <ul
                class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700"
            >
                <li v-for="category in categories" :key="category.id">
                    <Link
                        :href="
                            route('kalakalkoto.category.filter', category.id)
                        "
                        class="flex items-center p-1 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group"
                    >
                        <span class="ms-3 text-sm">{{ category.name }}</span>
                    </Link>
                </li>
            </ul> -->
        </div>
    </aside>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import logo from "/public/img/kalakalkoto.png";
</script>

<style scoped></style>
