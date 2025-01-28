<template>
	<nav class="w-full bg-white shadow rounded-lg mb-2">
		<div class="max-w-screen-xl px-4 py-3 mx-auto">
			<div class="flex items-center">
				<ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
					<!-- Home Link -->
					<li>
						<Link
							:href="route('kalakalkoto')"
							:class="{
								'text-gray-900 font-bold underline dark:text-white':
									route().current() === 'kalakalkoto',
								'text-gray-900 font-bold dark:text-white hover:underline':
									route().current() !== 'kalakalkoto',
							}">
							Home
						</Link>
					</li>

					<!-- Create Listing Link -->
					<li>
						<Link
							:href="route('kalakalkoto.create')"
							:class="{
								'text-gray-900 font-bold underline dark:text-white':
									route().current() === 'kalakalkoto.create',
								'text-gray-900 font-bold dark:text-white hover:underline':
									route().current() !== 'kalakalkoto.create',
							}">
							Create Listing
						</Link>
					</li>

					<!-- View My Listings Link -->
					<li>
						<Link
							:href="route('kalakalkoto.showUserItems')"
							:class="{
								'text-gray-900 font-bold underline dark:text-white':
									route().current() === 'kalakalkoto.showUserItems',
								'text-gray-900 font-bold dark:text-white hover:underline':
									route().current() !== 'kalakalkoto.showUserItems',
							}">
							View My Listings
						</Link>
					</li>

					<!-- Auction Link -->
					<!-- <li>
                        <Link
                            :href="route('auction.index')"
                            :class="{
                                'text-gray-900 font-bold underline dark:text-white':
                                    route().current() === 'auction.index',
                                'text-gray-900 font-bold dark:text-white hover:underline':
                                    route().current() !== 'auction.index',
                            }"
                        >
                            Auction
                        </Link>
                    </li> -->
				</ul>
			</div>
		</div>
	</nav>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>
