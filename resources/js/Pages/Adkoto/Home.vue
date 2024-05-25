<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import adsFirst from '/public/img/ads/testad1.jpg';
import adsSecond from '/public/img/ads/testad2.jpg';

import adsOne from '/public/img/ads/ADS 1.jpg';
import adsTwo from '/public/img/ads/ADS 2.jpg';
</script>

<template>

    <Head title="Adkoto" />

    <AuthenticatedLayout>
        <div class="max-w-7xl overflow-y-auto mx-auto gap-3 p-4 h-full">
            <h1 class="text-4xl font-bold mb-8">Adkoto</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="(ad, index) in ads" :key="index" class="border rounded-lg overflow-hidden bg-white">
                    <div class="h-40 overflow-hidden">
                        <img :src="ad.image" alt="Ad Image" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h2 class="text-xl font-bold mb-2">{{ ad.title }}</h2>
                        <p class="text-gray-600 mb-2">{{ ad.description }}</p>
                        <p class="text-orange-600 font-bold mb-2">{{ ad.price }}</p>
                        <p class="text-sm text-gray-500 mb-1">Location: {{ ad.location }}</p>
                        <p class="text-sm text-gray-500">Posted: {{ ad.date }}</p>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto my-6 grid grid-cols-2 gap-8 p-4">
                <img class="w-full h-full" :src="adsFirst" />
                <img class="w-full h-full" :src="adsSecond" />
            </div>

            <div class="bg-white">
                <div
                    class="mx-auto grid max-w-2xl grid-cols-1 items-center gap-x-8 gap-y-16 px-4 py-24 sm:px-6 sm:py-32 lg:max-w-7xl lg:grid-cols-2 lg:px-8">
                    <div>
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Discover the Ultimate in
                            Functionality and Style</h2>
                        <p class="mt-4 text-gray-500">Elevate your workspace with our precision-crafted walnut wood card
                            tray. Designed to perfection, it offers an elegant solution for organizing your Focus cards,
                            making your tasks more efficient and your desk more stylish.</p>

                        <dl class="mt-16 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 sm:gap-y-16 lg:gap-x-8">
                            <div v-for="feature in features" :key="feature.name" class="border-t border-gray-200 pt-4">
                                <dt class="font-medium text-gray-900">{{ feature.name }}</dt>
                                <dd class="mt-2 text-sm text-gray-500">{{ feature.description }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="grid grid-cols-2 grid-rows-2 gap-4 sm:gap-6 lg:gap-8">
                        <img src="https://tailwindui.com/img/ecommerce-images/product-feature-03-detail-01.jpg"
                            alt="Walnut card tray with white powder coated steel divider and 3 punchout holes."
                            class="rounded-lg bg-gray-100" />
                        <img src="https://tailwindui.com/img/ecommerce-images/product-feature-03-detail-02.jpg"
                            alt="Top down view of walnut card tray with embedded magnets and card groove."
                            class="rounded-lg bg-gray-100" />
                        <img src="https://tailwindui.com/img/ecommerce-images/product-feature-03-detail-03.jpg"
                            alt="Side of walnut card tray with card groove and recessed card area."
                            class="rounded-lg bg-gray-100" />
                        <img src="https://tailwindui.com/img/ecommerce-images/product-feature-03-detail-04.jpg"
                            alt="Walnut card tray filled with cards and card angled in dedicated groove."
                            class="rounded-lg bg-gray-100" />
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto my-6 grid grid-cols-2 gap-8 p-4">
                <img class="w-full h-full" :src="adsOne" />
                <img class="w-full h-full" :src="adsTwo" />
            </div>

            <div class="bg-white py-24 sm:py-32">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <h2 class="text-center text-lg font-semibold leading-8 text-gray-900">Featuring</h2>
                    <div
                        class="mx-auto mt-10 grid max-w-lg grid-cols-4 items-center gap-x-8 gap-y-10 sm:max-w-xl sm:grid-cols-6 sm:gap-x-10 lg:mx-0 lg:max-w-none lg:grid-cols-5">
                        <img class="col-span-2 max-h-12 w-full object-contain lg:col-span-1"
                            src="https://tailwindui.com/img/logos/158x48/transistor-logo-gray-900.svg" alt="Transistor"
                            width="158" height="48" />
                        <img class="col-span-2 max-h-12 w-full object-contain lg:col-span-1"
                            src="https://tailwindui.com/img/logos/158x48/reform-logo-gray-900.svg" alt="Reform"
                            width="158" height="48" />
                        <img class="col-span-2 max-h-12 w-full object-contain lg:col-span-1"
                            src="https://tailwindui.com/img/logos/158x48/tuple-logo-gray-900.svg" alt="Tuple"
                            width="158" height="48" />
                        <img class="col-span-2 max-h-12 w-full object-contain sm:col-start-2 lg:col-span-1"
                            src="https://tailwindui.com/img/logos/158x48/savvycal-logo-gray-900.svg" alt="SavvyCal"
                            width="158" height="48" />
                        <img class="col-span-2 col-start-2 max-h-12 w-full object-contain sm:col-start-auto lg:col-span-1"
                            src="https://tailwindui.com/img/logos/158x48/statamic-logo-gray-900.svg" alt="Statamic"
                            width="158" height="48" />
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto my-6 grid grid-cols-2 gap-8 p-4">
                <img class="w-full h-full" :src="adsFirst" />
                <img class="w-full h-full" :src="adsSecond" />
            </div>

            <div class="bg-white py-24 sm:py-5">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl lg:mx-0">
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">From the latest</h2>
                        <p class="mt-2 text-lg leading-8 text-gray-600">Learn how to grow your business.</p>
                    </div>
                    <div
                        class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                        <article v-for="post in posts" :key="post.id"
                            class="flex max-w-xl flex-col items-start justify-between">
                            <div class="flex items-center gap-x-4 text-xs">
                                <time :datetime="post.datetime" class="text-gray-500">{{ post.date }}</time>
                                <a :href="post.category.href"
                                    class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">{{
                                        post.category.title }}</a>
                            </div>
                            <div class="group relative">
                                <h3
                                    class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600">
                                    <a :href="post.href">
                                        <span class="absolute inset-0" />
                                        {{ post.title }}
                                    </a>
                                </h3>
                                <p class="mt-5 line-clamp-3 text-sm leading-6 text-gray-600">{{ post.description }}</p>
                            </div>
                            <div class="relative mt-8 flex items-center gap-x-4">
                                <img :src="post.author.imageUrl" alt="" class="h-10 w-10 rounded-full bg-gray-50" />
                                <div class="text-sm leading-6">
                                    <p class="font-semibold text-gray-900">
                                        <a :href="post.author.href">
                                            <span class="absolute inset-0" />
                                            {{ post.author.name }}
                                        </a>
                                    </p>
                                    <p class="text-gray-600">{{ post.author.role }}</p>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto my-6 grid grid-cols-2 gap-8 p-4">
                <img class="w-full h-full" :src="adsOne" />
                <img class="w-full h-full" :src="adsTwo" />
            </div>

            <footer class="bg-zinc-50 text-center dark:bg-neutral-700 lg:text-left">
                <div class="bg-black/5 p-4 text-center text-surface dark:text-white">
                    © 2024 Copyright:
                    <a href="#">Adkoto</a>
                </div>
            </footer>

        </div>

    </AuthenticatedLayout>
</template>

<script>
const posts = [
    {
        id: 2,
        title: 'Spacious Apartment for Rent',
        href: '#',
        description: 'A beautiful and spacious apartment in the heart of the city. Close to all amenities and public transport. Ideal for families and professionals.',
        date: 'Apr 10, 2023',
        datetime: '2023-04-10',
        category: { title: 'Real Estate', href: '#' },
        author: {
            name: 'Sarah Connor',
            role: 'Realtor',
            href: '#',
            imageUrl: 'https://images.unsplash.com/photo-1502685104226-ee32379fefbe?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        },
    },
    {
        id: 3,
        title: 'Used Car for Sale',
        href: '#',
        description: 'Well-maintained used car with low mileage. Great condition and regularly serviced. Contact for a test drive and more details.',
        date: 'May 05, 2024',
        datetime: '2024-05-05',
        category: { title: 'Automotive', href: '#' },
        author: {
            name: 'John Doe',
            role: 'Car Dealer',
            href: '#',
            imageUrl: 'https://images.unsplash.com/photo-1499996860823-5214fcc65f8f?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        },
    },
    {
        id: 4,
        title: 'Freelance Web Developer',
        href: '#',
        description: 'Experienced freelance web developer available for hire. Specializing in frontend and backend development with modern technologies.',
        date: 'Feb 20, 2023',
        datetime: '2023-02-20',
        category: { title: 'Services', href: '#' },
        author: {
            name: 'Alice Smith',
            role: 'Freelancer',
            href: '#',
            imageUrl: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        },
    }
]
const features = [
    { name: "Precision Milled", description: "Expertly crafted to fit your Focus cards perfectly." },
    { name: "Powder Coated Steel Divider", description: "Keeps your active cards separated from new ones." },
    { name: "Elegant Design", description: "A stylish addition to any workspace." },
    { name: "Durable Walnut Wood", description: "Built to last and withstand daily use." },
    { name: "Magnetic Closure", description: "Ensures your cards stay secure and organized." },
    { name: "Versatile Use", description: "Ideal for task lists, notes, and important reminders." }
]
export default {
    components: {
        ads: Array
    },
    data() {
        return {
            ads: [
                {
                    id: 1,
                    title: "Japanese Tea Set",
                    description: "Traditional Japanese tea set with teapot, cups, and saucers. Perfect for tea ceremonies.",
                    price: "₱50",
                    location: "Tokyo",
                    date: "May 24, 2024",
                    image: "https://picsum.photos/seed/p6/400/300"
                },
                {
                    id: 2,
                    title: "Room for Rent",
                    description: "Spacious room for rent in a shared apartment. Close to public transport and amenities.",
                    price: "₱600/month",
                    location: "Osaka",
                    date: "May 23, 2024",
                    image: "https://picsum.photos/seed/p6/400/300"
                },
                {
                    id: 3,
                    title: "Vintage Kimono",
                    description: "Beautiful vintage kimono in excellent condition. Perfect for special occasions or display.",
                    price: "₱150",
                    location: "Kyoto",
                    date: "May 22, 2024",
                    image: "https://picsum.photos/seed/p6/400/300"
                }
            ]
        };
    },
}
</script>
