<script setup>
import { Link, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>

    <Head title="Kalakalkoto" />

    <AuthenticatedLayout>
        <div class="grid max-w-7xl mx-auto lg:grid-cols-12 gap-3 p-4 h-full">
            <!-- Sidebar -->
            <div class="lg:col-span-3 lg:order-1 h-full overflow-hidden">
                <div class="px-3 bg-white dark:bg-slate-950 rounded border dark:border-slate-900 dark:text-gray-100 h-full py-3 overflow-hidden">
                    <!-- Mobile Menu Button -->
                    <!-- <button @click="toggleSidebar"
                        class="text-gray-500 dark:text-gray-200 focus:outline-none focus:text-gray-600 dark:focus:text-gray-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button> -->
                    <!-- Sidebar Content -->
                    <div :class="sidebarOpen ? 'block' : 'hidden'" class="h-full overflow-hidden flex-col lg:flex">
                        <div class="flex justify-between mb-4">
                            <h2 class="text-xl font-bold">Kalakalkoto</h2>
                        </div>
                        <nav class="flex-1 overflow-y-auto">
                            <ul>
                                <li class="mb-2">
                                    <button @click="showProducts"
                                        class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800">Home</button>
                                </li>
                                <li class="mb-2">
                                    <button @click="showTopSellers"
                                        class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800">Top
                                        Sellers</button>
                                </li>
                                <!-- Add more sidebar buttons as needed -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- Main Content Area -->
            <div class="lg:col-span-9 lg:order-2 h-full overflow-y-auto flex flex-col">
                <div v-if="currentView === 'products'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <router-link v-for="product in products" :key="product.id" :to="route('product', { id: product.id })">
                        <div class="bg-white dark:bg-slate-950 rounded-lg border dark:border-slate-900 p-4 flex flex-col items-center">
                            <img :src="product.image" :alt="product.name" class="w-full h-48 object-cover mb-4 rounded">
                            <h3 class="text-lg font-semibold mb-2">{{ product.name }}</h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-2">{{ product.description }}</p>
                            <p class="text-xl font-bold mb-4">₱{{ product.price }}</p>
                            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"><Link :href="route('product')">View</Link></button>
                        </div>
                    </router-link>
                </div>
                <div v-else-if="currentView === 'topSellers'" class="flex justify-center items-center h-full">
                    <h1 class="text-3xl font-bold">No Top Selling Items</h1>
                    <!-- Render top sellers content here -->
                </div>
                <!-- Add more conditional rendering for other views -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    components: {
        Head,
        AuthenticatedLayout
    },
    data() {
        return {
            sidebarOpen: false,
            currentView: 'products', // Default to showing products
            products: [
                {
                    id: 1,
                    name: "Gadget Set",
                    description: "All in one gadget set",
                    price: 30000.99,
                    image: "https://fastly.picsum.photos/id/26/4209/2769.jpg?hmac=vcInmowFvPCyKGtV7Vfh7zWcA_Z0kStrPDW3ppP0iGI"
                },
                {
                    id: 2,
                    name: "Mug",
                    description: "Normal na Tasa",
                    price: 39.99,
                    image: "https://fastly.picsum.photos/id/30/1280/901.jpg?hmac=A_hpFyEavMBB7Dsmmp53kPXKmatwM05MUDatlWSgATE"
                },
                {
                    id: 3,
                    name: "Macbook Lite",
                    description: "Apple is Good",
                    price: 80000.99,
                    image: "https://fastly.picsum.photos/id/48/5000/3333.jpg?hmac=y3_1VDNbhii0vM_FN6wxMlvK27vFefflbUSH06z98so"
                },
                {
                    id: 4,
                    name: "Black Car",
                    description: "Kotse na pwede pang bundok at City Ride",
                    price: 4000000.09,
                    image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR8Ez0J_de9gB_5L3kOx8EmESc3y2vuei6SFY3jwOfwPw&s"
                },
                {
                    id: 5,
                    name: "Cellphoneee",
                    description: "Malinaw yung Front Camera",
                    price: 8000.99,
                    image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSSex6r9WMKzBH3qkpD3Y0vctsh4lJCv2Gp1OIUg0dgHA&s"
                },
                // Add more products here
                {
                    id: 6,
                    name: "Product 6",
                    description: "Description for product 6",
                    price: 34.99,
                    image: "https://picsum.photos/seed/p6/400/300"
                },
                {
                    id: 7,
                    name: "Product 7",
                    description: "Description for product 7",
                    price: 44.99,
                    image: "https://picsum.photos/seed/p7/400/300"
                },
                {
                    id: 8,
                    name: "Product 8",
                    description: "Description for product 8",
                    price: 24.99,
                    image: "https://picsum.photos/seed/p8/400/300"
                },
                {
                    id: 9,
                    name: "Product 9",
                    description: "Description for product 9",
                    price: 54.99,
                    image: "https://picsum.photos/seed/p9/400/300"
                },
                {
                    id: 10,
                    name: "Product 10",
                    description: "Description for product 10",
                    price: 64.99,
                    image: "https://picsum.photos/seed/p10/400/300"
                },
                {
                    id: 11,
                    name: "Product 11",
                    description: "Description for product 11",
                    price: 29.99,
                    image: "https://picsum.photos/seed/p11/400/300"
                },
                {
                    id: 12,
                    name: "Product 12",
                    description: "Description for product 12",
                    price: 39.99,
                    image: "https://picsum.photos/seed/p12/400/300"
                },
                {
                    id: 13,
                    name: "Product 13",
                    description: "Description for product 13",
                    price: 19.99,
                    image: "https://picsum.photos/seed/p13/400/300"
                },
                {
                    id: 14,
                    name: "Product 14",
                    description: "Description for product 14",
                    price: 49.99,
                    image: "https://picsum.photos/seed/p14/400/300"
                },
                {
                    id: 15,
                    name: "Product 15",
                    description: "Description for product 15",
                    price: 59.99,
                    image: "https://picsum.photos/seed/p15/400/300"
                }
            ]
        };
    },
    methods: {
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },
        showProducts() {
            this.currentView = 'products';
        },
        showTopSellers() {
            this.currentView = 'topSellers';
        }
        // Add more methods for other views as needed
    }
};
</script>

<style scoped>
/* Add any additional styles here */
</style>
