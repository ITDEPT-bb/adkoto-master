<template>
    <div
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 backdrop-blur-sm"
    >
        <div
            class="mx-4 w-full max-w-md rounded-2xl bg-white p-8 text-center shadow-2xl dark:bg-slate-900"
        >
            <div
                class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-10 w-10 text-red-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 15v2m0-8h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"
                    />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Wallet Balance Too Low
            </h2>

            <p class="mt-3 text-gray-600 dark:text-gray-400">
                You need at least
                <span class="font-semibold text-red-600">₱1,000.00</span>
                in your wallet before you can participate in the live auction.
            </p>

            <div class="mt-8 flex flex-col gap-3">
                <button
                    @click="$emit('recharge')"
                    class="w-full rounded-lg bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700"
                >
                    Recharge Wallet
                </button>

                <Link
                    :href="route('auction')"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-100 px-5 py-3 text-center font-semibold text-gray-700 transition hover:bg-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700"
                >
                    Return Home
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineEmits(["recharge"]);
</script>