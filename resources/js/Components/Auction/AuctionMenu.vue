<template>
	<nav class="w-full bg-white shadow rounded-lg mb-2">
		<div
			class="max-w-screen-xl px-4 mx-auto flex flex-col md:flex-row md:justify-between md:items-center">
			<div class="flex items-center justify-between md:justify-start w-full md:w-auto">
				<!-- Logo or Branding (Optional) -->
				<h1 class="text-lg font-bold md:hidden">Auction Menu</h1>

				<!-- Mobile Menu Toggle Button -->
				<button
					@click="toggleMobileMenu"
					class="text-gray-500 md:hidden p-2 focus:outline-none"
					aria-label="Toggle navigation">
					<svg
						class="w-6 h-6"
						fill="none"
						stroke="currentColor"
						viewBox="0 0 24 24"
						xmlns="http://www.w3.org/2000/svg">
						<path
							stroke-linecap="round"
							stroke-linejoin="round"
							stroke-width="2"
							d="M4 6h16M4 12h16M4 18h16"></path>
					</svg>
				</button>
			</div>

			<!-- Navigation Links -->
			<div
				:class="{ block: isMobileMenuOpen, hidden: !isMobileMenuOpen }"
				class="md:flex md:items-center w-full md:w-auto">
				<ul
					class="flex flex-col md:flex-row font-medium mt-2 md:mt-0 space-y-2 md:space-y-0 md:space-x-8 rtl:space-x-reverse text-sm">
					<!-- Home Link -->
					<li>
						<a
							:href="route('auction')"
							:class="{
								'text-gray-900 font-bold underline dark:text-white':
									route().current() === 'auction',
								'text-gray-900 font-bold dark:text-white hover:underline':
									route().current() !== 'auction',
							}">
							Home
						</a>
					</li>
					<!-- Other Links -->
					<li>
						<a
							:href="route('auction.showUserItems')"
							:class="{
								'text-gray-900 font-bold underline dark:text-white':
									route().current() === 'auction.showUserItems',
								'text-gray-900 font-bold dark:text-white hover:underline':
									route().current() !== 'auction.showUserItems',
							}">
							View My Items
						</a>
					</li>
					<!-- <li>
						<a
							:href="route('auction.viewAllLive')"
							:class="{
								'text-gray-900 font-bold underline dark:text-white':
									route().current() === 'auction.viewAllLive',
								'text-gray-900 font-bold dark:text-white hover:underline':
									route().current() !== 'auction.viewAllLive',
							}">
							Live Bidding
						</a>
					</li> -->
				</ul>
			</div>

			<!-- Balance and Recharge Icon -->
			<div class="flex items-center justify-between md:justify-center py-2 gap-2 mt-1 md:mt-0">
				<div class="flex justify-center items-center">
					<h1 class="text-md font-semibold">Your Balance:</h1>
					<span class="ml-1 text-lg font-medium"> ₱{{ walletBalance }} </span>
				</div>
				<PlusCircleIcon
					class="h-5 w-5 cursor-pointer hover:scale-125 transition-all"
					@click="openModal" />
			</div>
		</div>

		<!-- Recharge Modal -->
		<RechargeModal
			:isOpen="isModalOpen"
			@close="closeModal"
			@recharge="handleRecharge" />
	</nav>
</template>

<script setup>
import { ref } from "vue";
import { PlusCircleIcon } from "@heroicons/vue/24/outline";
import { usePage } from "@inertiajs/vue3";
import RechargeModal from "@/Components/Auction/RechargeModal.vue";

const { walletBalance } = usePage().props;
const isModalOpen = ref(false);
const isMobileMenuOpen = ref(false);

function openModal() {
	isModalOpen.value = true;
}

function closeModal() {
	isModalOpen.value = false;
}

function handleRecharge(amount) {
	console.log("Recharging with amount:", amount);
}

function toggleMobileMenu() {
	isMobileMenuOpen.value = !isMobileMenuOpen.value;
}
</script>
