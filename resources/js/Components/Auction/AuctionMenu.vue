<template>
    <nav class="w-full bg-white shadow rounded-lg mb-2">
        <div class="max-w-screen-xl px-4 py-3 mx-auto">
            <div class="flex items-center">
                <ul
                    class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm"
                >
                    <!-- Home Link -->
                    <li>
                        <a
                            :href="route('auction')"
                            :class="{
                                'text-gray-900 font-bold underline dark:text-white':
                                    route().current() === 'auction',
                                'text-gray-900 font-bold dark:text-white hover:underline':
                                    route().current() !== 'auction',
                            }"
                        >
                            Home
                        </a>
                    </li>

                    <!-- Create Listing Link -->
                    <li>
                        <a
                            href="/auction/create"
                            :class="{
                                'text-gray-900 font-bold underline dark:text-white':
                                    route().current() === 'auction.create',
                                'text-gray-900 font-bold dark:text-white hover:underline':
                                    route().current() !== 'auction.create',
                            }"
                        >
                            Create New Item
                        </a>
                    </li>

                    <!-- View My Listings Link -->
                    <li>
                        <a
                            href="#"
                            :class="{
                                'text-gray-900 font-bold underline dark:text-white':
                                    route().current() ===
                                    'auction.showUserItems',
                                'text-gray-900 font-bold dark:text-white hover:underline':
                                    route().current() !==
                                    'auction.showUserItems',
                            }"
                        >
                            View My Items
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script setup></script>
