<template>
    <section class="min-h-screen bg-tribekoto bg-cover bg-no-repeat pb-4">
        <div
            class="max-w-7xl mx-auto px-6 py-16 flex flex-col justify-between h-full md:h-screen"
        >
            <!-- TOP CONTENT -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                <div class="space-y-8">
                    <img
                        src="/assets/logo/tribekoto.png"
                        alt="Tribekoto Logo"
                        class="w-48 sm:w-60 md:w-96 border-b-8 border-brand-blue"
                    />

                    <h1
                        class="text-2xl sm:text-3xl md:text-6xl font-garmint text-brand-red leading-snug tracking-widest uppercase"
                    >
                        A HUB TO SHARE, CONNECT, <br />
                        AND GROW TOGETHER.
                    </h1>

                    <p
                        class="text-base sm:text-xl text-gray-700 max-w-lg tracking-wide font-semibold"
                    >
                        Tribekoto is a community platform where people can
                        connect, share ideas, and build meaningful online
                        tribes.
                    </p>

                    <a
                        href="#"
                        class="inline-block bg-brand-blue text-white font-semibold px-8 py-3 rounded-lg shadow-md hover:bg-blue-700 transition"
                    >
                        Learn More...
                    </a>
                </div>

                <div></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-12">
                <!-- Feature 1 -->
                <div
                    class="flex items-center gap-4 border-2 border-brand-blue rounded-2xl p-4 bg-white shadow-sm"
                >
                    <div
                        class="flex items-center justify-center w-20 h-20 rounded-full"
                    >
                        <img
                            src="/assets/icon/authentic_network.png"
                            alt="Authentic Network"
                            class="w-20 h-auto"
                        />
                    </div>

                    <div class="text-left">
                        <h3
                            class="text-brand-blue font-bold text-sm sm:text-base"
                        >
                            Authentic Network
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-600">
                            Suggests a trusted space where people interact
                            honestly and build meaningful relationships.
                        </p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div
                    class="flex items-center gap-4 border-2 border-brand-blue rounded-2xl p-4 bg-white shadow-sm"
                >
                    <div
                        class="flex items-center justify-center w-20 h-20 rounded-full"
                    >
                        <img
                            src="/assets/icon/real_connections.png"
                            alt="Real Connections"
                            class="w-20 h-auto"
                        />
                    </div>

                    <div class="text-left">
                        <h3
                            class="text-brand-blue font-bold text-sm sm:text-base"
                        >
                            Real Connections
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-600">
                            Highlights genuine human interaction, not fake or
                            automated engagement.
                        </p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div
                    class="flex items-center gap-4 border-2 border-brand-blue rounded-2xl p-4 bg-white shadow-sm"
                >
                    <div
                        class="flex items-center justify-center w-20 h-20 rounded-full"
                    >
                        <img
                            src="/assets/icon/connect_and_share.png"
                            alt="Connect & Share"
                            class="w-20 h-auto"
                        />
                    </div>

                    <div class="text-left">
                        <h3
                            class="text-brand-blue font-bold text-sm sm:text-base"
                        >
                            Connect & Share
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-600">
                            Bringing people together to share ideas and stories.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup></script>
