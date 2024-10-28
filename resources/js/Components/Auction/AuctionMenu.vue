<template>
	<nav class="w-full bg-white shadow rounded-lg mb-2">
		<div class="max-w-screen-xl px-4 mx-auto flex flex-row justify-between items-center">
			<div class="flex items-center">
				<ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
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
					<li>
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
					</li>
				</ul>
			</div>
			<div class="flex items-center justify-center p-2 gap-2">
				<div class="flex justify-center items-center">
					<h1 class="text-md font-semibold">Your Balance:</h1>
					<span class="ml-1 text-lg font-medium"> ₱{{ walletBalance }} </span>
				</div>
				<PlusCircleIcon
					class="h-5 w-5 cursor-pointer hover:scale-125 transition-all"
					@click="openModal" />
			</div>
		</div>
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

function openModal() {
	isModalOpen.value = true;
}

function closeModal() {
	isModalOpen.value = false;
}

function handleRecharge(amount) {
	console.log("Recharging with amount:", amount);
}
</script>
