<template>
    <nav class="w-full bg-white shadow rounded-lg mb-2">
        <div class="max-w-screen-xl px-4 py-3 mx-auto">
            <div class="flex items-center">
                <ul
                    class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm"
                >
                    <!-- Navigation Items with spacing -->
                    <li>
                        <a
                            :href="route('kalakalkoto')"
                            class="text-gray-900 font-bold dark:text-white hover:underline"
                        >
                            Home
                        </a>
                    </li>
                    <li>
                        <a
                            href="/kalakalkoto/create"
                            class="text-gray-900 font-bold dark:text-white hover:underline"
                        >
                            Create Listing
                        </a>
                    </li>
                    <li>
                        <a
                            href="/kalakalkoto"
                            class="text-gray-900 font-bold dark:text-white hover:underline"
                        >
                            View My Listings
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script setup></script>
