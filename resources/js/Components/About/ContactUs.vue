<template>
    <section class="min-h-screen bg-contactus bg-cover bg-no-repeat py-20 px-6">
        <div class="max-w-7xl mx-auto text-center">
            <!-- Heading -->
            <h1
                class="uppercase font-boldmarker text-brand-blue text-5xl md:text-7xl tracking-wide mb-4"
            >
                Contact Us
            </h1>
            <h6 class="text-lg md:text-xl text-gray-700 mb-10">
                Have questions? Connect with us anytime at Adkoto. Our team is
                ready to assist you with inquiries, support, or guidance to make
                your experience smooth and hassle-free.
            </h6>
        </div>

        <!-- MAP -->
        <div class="max-w-7xl mx-auto mb-10">
            <div class="w-full h-80 rounded-lg overflow-hidden shadow">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3851.585646970067!2d120.59506977587291!3d15.126148463992305!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396ed4741c8100d%3A0x6d496fd20153855a!2sBB%2088%20ADVERTISING%20AND%20DIGITAL%20SOLUTIONS!5e0!3m2!1sen!2sph!4v1757667004278!5m2!1sen!2sph"
                    width="600"
                    height="450"
                    style="border: 0"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    class="w-full h-full border-0"
                ></iframe>
            </div>
        </div>

        <!-- CONTACT INFO + FORM -->
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-10">
            <!-- LEFT SIDE: contact info -->
            <div class="space-y-6">
                <!-- Phone -->
                <div class="p-6 bg-white rounded shadow flex items-center">
                    <svg
                        class="w-6 h-6 text-brand-green mr-3"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 5a2 2 0 012-2h2.28a2 2 0 011.94 1.51l.57 2.3a2 2 0 01-.45 1.79l-1.2 1.2a16 16 0 006.36 6.36l1.2-1.2a2 2 0 011.79-.45l2.3.57A2 2 0 0121 18.72V21a2 2 0 01-2 2h-1C9.163 23 1 14.837 1 5V4a2 2 0 012-2z"
                        />
                    </svg>
                    <div>
                        <p class="text-brand-blue font-semibold text-sm">
                            Phone
                        </p>
                        <p class="text-gray-800 font-semibold">
                            +63 917 501 0018
                        </p>
                    </div>
                </div>

                <!-- Email -->
                <div class="p-6 bg-white rounded shadow flex items-center">
                    <svg
                        class="w-6 h-6 text-brand-green mr-3"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M16 12H8m8 0L8 6m0 6l8 6M2 5a2 2 0 012-2h16a2 2 0 012 2v14a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"
                        />
                    </svg>
                    <div>
                        <p class="text-brand-blue font-semibold text-sm">
                            Email
                        </p>
                        <p class="text-gray-800 font-semibold">
                            adkotophilippines@gmail.com
                        </p>
                    </div>
                </div>

                <!-- Social -->
                <div>
                    <p class="font-semibold text-gray-700 mb-2">Follow Us on</p>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:opacity-80 transition">
                            <img
                                src="/assets/icon/adkoto_icon.png"
                                alt="Adkoto"
                                class="w-12 h-auto"
                            />
                        </a>
                        <a href="#" class="hover:opacity-80 transition">
                            <img
                                src="/assets/icon/fb_icon.png"
                                alt="Facebook"
                                class="w-12 h-auto"
                            />
                        </a>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE: contact form -->
            <div class="p-6 bg-white rounded shadow">
                <form class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <input
                            type="text"
                            placeholder="Name"
                            class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-brand-green"
                        />
                        <input
                            type="email"
                            placeholder="Email"
                            class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-brand-green"
                        />
                    </div>

                    <input
                        type="text"
                        placeholder="Subject"
                        class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-brand-green"
                    />

                    <textarea
                        placeholder="Message"
                        rows="4"
                        class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-brand-green"
                    ></textarea>

                    <button
                        type="submit"
                        class="bg-brand-green text-white font-semibold px-6 py-3 rounded hover:bg-green-600 transition"
                    >
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>
</template>

<script setup></script>
