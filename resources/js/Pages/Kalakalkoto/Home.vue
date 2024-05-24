<template>
    <Head title="Kalakalkoto" />

    <AuthenticatedLayout>
        <div class="grid max-w-7xl mx-auto lg:grid-cols-12 gap-3 p-4 h-full">
            <div class="lg:col-span-3 lg:order-1 h-full overflow-hidden">
                <div class="px-3 bg-white dark:bg-slate-950 rounded border dark:border-slate-900 dark:text-gray-100 h-full py-3 overflow-hidden">
                    <div class="block lg:hidden">
                        <!-- Mobile Menu Button -->
                        <button @click="toggleMobileMenu" class="text-gray-500 dark:text-gray-200 focus:outline-none focus:text-gray-600 dark:focus:text-gray-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="h-full overflow-hidden flex-col hidden lg:flex">
                        <div class="flex justify-between mb-4">
                            <h2 class="text-xl font-bold">Kalakalkoto</h2>
                        </div>
                        <nav class="flex-1 overflow-y-auto">
                            <ul>
                                <li class="mb-2">
                                    <a href="#" class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800">Home</a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800">Categories</a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800">Top Sellers</a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800">New Arrivals</a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800">Contact Us</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <!-- Mobile Menu -->
                    <div v-if="isMobileMenuOpen" class="lg:hidden">
                        <nav class="mt-4">
                            <ul>
                                <li class="mb-2">
                                    <a href="#" class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800">Home</a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800">Categories</a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800">Top Sellers</a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800">New Arrivals</a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800">Contact Us</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-9 lg:order-2 h-full overflow-y-auto flex flex-col">
                <!-- Main Content Area -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="product in products" :key="product.id" class="bg-white dark:bg-slate-950 rounded-lg border dark:border-slate-900 p-4 flex flex-col items-center">
                        <img :src="product.image" :alt="product.name" class="w-full h-48 object-cover mb-4 rounded">
                        <h3 class="text-lg font-semibold mb-2">{{ product.name }}</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-2">{{ product.description }}</p>
                        <p class="text-xl font-bold mb-4">${{ product.price }}</p>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Buy</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        Head,
        AuthenticatedLayout
    },
    data() {
        return {
            isMobileMenuOpen: false,
            products: [
                {
                    id: 1,
                    name: "Product 1",
                    description: "Description for product 1",
                    price: 29.99,
                    image: "https://fastly.picsum.photos/id/26/4209/2769.jpg?hmac=vcInmowFvPCyKGtV7Vfh7zWcA_Z0kStrPDW3ppP0iGI"
                },
                {
                    id: 2,
                    name: "Product 2",
                    description: "Description for product 2",
                    price: 39.99,
                    image: "https://fastly.picsum.photos/id/30/1280/901.jpg?hmac=A_hpFyEavMBB7Dsmmp53kPXKmatwM05MUDatlWSgATE"
                },
                {
                    id: 3,
                    name: "Product 3",
                    description: "Description for product 3",
                    price: 19.99,
                    image: "https://fastly.picsum.photos/id/48/5000/3333.jpg?hmac=y3_1VDNbhii0vM_FN6wxMlvK27vFefflbUSH06z98so"
                },
                {
                    id: 4,
                    name: "Product 4",
                    description: "Description for product 4",
                    price: 49.99,
                    image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR8Ez0J_de9gB_5L3kOx8EmESc3y2vuei6SFY3jwOfwPw&s"
                },
                {
                    id: 5,
                    name: "Product 5",
                    description: "Description for product 5",
                    price: 59.99,
                    image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSSex6r9WMKzBH3qkpD3Y0vctsh4lJCv2Gp1OIUg0dgHA&s"
                }
            ]
        };
    },
    methods: {
        toggleMobileMenu() {
            this.isMobileMenuOpen = !this.isMobileMenuOpen;
        }
    }
};
</script>

<style scoped>
/* Add any additional styles here */
</style>
