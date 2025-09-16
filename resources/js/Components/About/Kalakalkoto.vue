<template>
    <section
        class="min-h-screen bg-kalakalkoto bg-cover bg-no-repeat py-12 px-4 sm:px-6 lg:py-20 lg:px-8"
    >
        <div class="max-w-7xl mx-auto text-center">
            <!-- Heading -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 items-center"
            >
                <!-- Left: Logo & Text -->
                <div class="space-y-6 text-left">
                    <!-- Logo -->
                    <img
                        src="/assets/logo/kalakalkoto.png"
                        alt="Kalakalkoto Logo"
                        class="w-40 sm:w-56 md:w-72 lg:w-96 mx-auto md:mx-0"
                    />

                    <p
                        class="text-base sm:text-lg md:text-xl text-brand-blue font-semibold leading-relaxed"
                    >
                        Learn how to buy, sell, and connect with real people on
                        Kalakalkoto. This tutorial also shows you step by step
                        how to register and start your journey.
                    </p>

                    <Link
                        :href="route('auction.login')"
                        class="inline-block bg-brand-green text-white border-2 border-brand-lightgreen font-semibold px-6 sm:px-8 md:px-10 py-2 sm:py-3 rounded-lg shadow-md hover:bg-green-700 transition"
                    >
                        Register now!
                    </Link>
                </div>

                <!-- Right: Video -->
                <div
                    class="bg-white rounded-2xl shadow-lg flex items-center justify-center text-xl sm:text-2xl md:text-3xl font-semibold h-48 sm:h-64 md:h-80 relative"
                >
                    VIDEO
                    <!-- Controls -->
                    <button
                        class="absolute left-2 sm:left-4 bg-gray-200 rounded-full p-2 hover:bg-gray-300"
                    >
                        ◀
                    </button>
                    <button
                        class="absolute right-2 sm:right-4 bg-gray-200 rounded-full p-2 hover:bg-gray-300"
                    >
                        ▶
                    </button>
                </div>
            </div>

            <!-- Card -->
            <div
                class="mt-12 sm:mt-16 relative rounded-2xl overflow-hidden shadow-lg bg-auctionvector bg-cover bg-no-repeat"
            >
                <!-- Overlay for text box -->
                <div
                    class="bg-white/90 border-2 sm:border-4 border-brand-blue rounded-xl p-4 sm:p-6 md:p-10 max-w-full sm:max-w-4xl ml-auto my-6 sm:my-10 sm:mr-6"
                >
                    <p
                        class="text-sm sm:text-base md:text-lg font-semibold text-brand-blue leading-relaxed"
                    >
                        This segment of Kalakalkoto functions as a marketplace
                        where users can engage in direct buying and selling with
                        one another. It facilitates secure transactions and peer
                        to peer interactions. It also features an auction where
                        real people directly deal with each other for the items
                        that appear in the bidding.
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>
