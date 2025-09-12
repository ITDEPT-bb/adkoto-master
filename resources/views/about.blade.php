<!doctype html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
</head>

<body>
    <div style="background-image: url('/assets/bg/1_bg.jpg')" class="min-h-screen bg-cover bg-no-repeat">
        <!-- Navbar -->
        <header class="pt-6">
            <div class="max-w-7xl mx-auto">
                <!-- big rounded pill -->
                <div
                    class="bg-white rounded-full border-4 border-brand-blue shadow-xl flex items-center gap-6 px-6 py-2">
                    <!-- left: logo -->
                    <div class="flex items-center">
                        <img src="/assets/logo/logo_header.png" alt="Adkoto Logo" class="h-12" />
                    </div>

                    <!-- center: nav -->
                    <nav class="hidden md:flex flex-1 justify-center items-center space-x-6 text-gray-700 font-medium">
                        <a href="#"
                            class="bg-brand-green text-white px-4 py-1 rounded-md font-semibold shadow-sm shadow-green-200/40">
                            Home
                        </a>
                        <a href="#" class="hover:text-blue-700 transition">About us</a>
                        <a href="#" class="hover:text-blue-700 transition">Features</a>
                        <a href="#" class="hover:text-blue-700 transition">Testimonials</a>
                        <a href="#" class="hover:text-blue-700 transition">Contact us</a>
                    </nav>

                    <!-- right: login -->
                    <div class="flex items-center">
                        <a href="#"
                            class="inline-flex items-center px-6 py-1 rounded-full bg-brand-blue text-white font-semibold shadow-md hover:bg-blue-700 transition">
                            Log in
                        </a>
                    </div>
                </div>
            </div>
        </header>


        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <!-- Left Content -->
            <div>
                <h1 class="text-3xl md:text-5xl font-bold text-white">
                    {{-- Welcome to <span class="text-green-600">Adkoto</span> --}}
                    Welcome to Adkoto
                </h1>
                <h2 class="text-2xl md:text-4xl font-boldmarker text-brand-blue mt-2">
                    WHERE REAL HUMANS CONNECT
                </h2>
                <p class="text-gray-700 mt-4">
                    This is your space to connect, share, and discover. Here, you can express your thoughts,
                    showcase your passions, and engage with people who share your interests. Whether you’re here
                    to make new friends, stay updated, or simply be inspired, our community is ready to welcome
                    you with open arms. Your story matters and this is the place to tell it.
                </p>

                <!-- Download Buttons -->
                <div class="mt-6">
                    <h3 class="font-extrabold text-green-600 text-lg">DOWNLOAD ADKOTO APP:</h3>
                    <div class="flex flex-wrap gap-4 mt-4">
                        <a href="#"
                            class="flex items-center px-4 py-2 bg-white shadow rounded-lg border hover:bg-gray-50 w-60">
                            <img src="/assets/icon/download_icon.png" class="h-6 mr-3" />
                            <span>Coming Soon<br /><strong>iOS App Store</strong></span>
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-2 bg-white shadow rounded-lg border hover:bg-gray-50 w-60">
                            <img src="/assets/icon/download_icon.png" class="h-6 mr-3" />
                            <span>Download<br /><strong>Google Play Store</strong></span>
                        </a>
                    </div>
                </div>
            </div>

        </section>

        <!-- Features -->
        <section class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="p-6 bg-white shadow rounded-lg text-center">
                <img src="/assets/icon/connect_with_real_people.png" class="mx-auto h-16 mb-4" />
                <h4 class="font-bold text-lg">Connect with real people</h4>
                <a href="#"
                    class="mt-4 inline-block px-4 py-2 border rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100">
                    Learn more...
                </a>
            </div>

            <div class="p-6 bg-white shadow rounded-lg text-center">
                <img src="/assets/icon/advertise_your_brands.png" class="mx-auto h-16 mb-4" />
                <h4 class="font-bold text-lg">Advertise your brands</h4>
                <a href="#"
                    class="mt-4 inline-block px-4 py-2 border rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100">
                    Learn more...
                </a>
            </div>

            <div class="p-6 bg-white shadow rounded-lg text-center">
                <img src="/assets/icon/sell_your_products.png" class="mx-auto h-16 mb-4" />
                <h4 class="font-bold text-lg">Sell your products</h4>
                <a href="#"
                    class="mt-4 inline-block px-4 py-2 border rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100">
                    Learn more...
                </a>
            </div>

            <div class="p-6 bg-white shadow rounded-lg text-center">
                <img src="/assets/icon/no_ai.png" class="mx-auto h-16 mb-4" />
                <h4 class="font-bold text-lg">No A.I. (Artificial Intelligence)</h4>
                <a href="#"
                    class="mt-4 inline-block px-4 py-2 border rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100">
                    Learn more...
                </a>
            </div>
        </section>
    </div>


</body>

</html>
