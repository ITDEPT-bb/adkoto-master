<template>
    <section class="relative min-h-screen bg-hero bg-cover bg-no-repeat pb-4">
        <!-- Floating Navbar -->
        <div class="absolute top-6 left-0 w-full z-10">
            <Navbar />
        </div>

        <div
            class="px-6 py-20 lg:py-32 grid max-w-7xl mx-auto grid-cols-1 md:grid-cols-3 gap-8 items-center"
        >
            <div class="col-span-2">
                <h1
                    class="text-3xl md:text-5xl font-garmint tracking-widest text-white mt-12 lg:mt-0"
                >
                    Welcome to Adkoto
                </h1>
                <h2
                    class="text-2xl md:text-5xl tracking-wide font-boldmarker text-brand-blue title-webkit mt-2"
                >
                    WHERE REAL HUMANS CONNECT
                </h2>
                <p class="text-white leading-7 text-md md:text-xl mt-3">
                    This is your space to connect, share, and discover. Here,
                    you can express your thoughts, showcase your passions, and
                    engage with people who share your interests. Whether you’re
                    here to make new friends, stay updated, or simply be
                    inspired, our community is ready to welcome you with open
                    arms. Your story matters and this is the place to tell it.
                </p>

                <!-- Download Buttons -->
                <div class="mt-6 px-4">
                    <h3
                        class="font-boldmarker title-webkit text-brand-lightgreen text-xl md:text-4xl"
                    >
                        DOWNLOAD ADKOTO APP:
                    </h3>

                    <div
                        class="flex flex-col sm:flex-row sm:flex-wrap gap-4 mt-4"
                    >
                        <!-- iOS Button -->
                        <a
                            href="#"
                            class="flex items-center px-4 py-3 bg-white rounded-lg hover:bg-gray-100 shadow-sm w-full sm:w-60"
                        >
                            <img
                                src="/assets/icon/download_icon.png"
                                class="h-6 mr-3"
                                alt="Download icon"
                            />
                            <span class="text-left text-sm">
                                Coming Soon<br />
                                <strong>iOS App Store</strong>
                            </span>
                        </a>

                        <!-- Android Button -->
                        <a
                            href="#"
                            class="flex items-center px-4 py-3 bg-white rounded-lg hover:bg-gray-100 shadow-sm w-full sm:w-60"
                        >
                            <img
                                src="/assets/icon/download_icon.png"
                                class="h-6 mr-3"
                                alt="Download icon"
                            />
                            <span class="text-left text-sm">
                                Download<br />
                                <strong>Google Play Store</strong>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Features -->
        <div class="lg:absolute bottom-0 left-0 w-full z-10">
            <Features />
        </div>
    </section>
</template>

<script setup>
import Navbar from "./Navbar.vue";
import Features from "./Features.vue";
</script>
