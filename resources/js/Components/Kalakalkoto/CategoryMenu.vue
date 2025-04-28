<template>
    <div class="w-full bg-white dark:bg-slate-950 shadow rounded-lg">
        <div class="mx-auto w-full rounded-2xl bg-white dark:bg-slate-950 p-2">
            <Disclosure v-slot="{ open }">
                <DisclosureButton
                    class="flex w-full justify-between rounded-lg px-4 py-2 text-left text-lg font-bold text-dark hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500/75 dark:text-white dark:hover:bg-slate-800"
                >
                    <span>Categories</span>
                    <ChevronUpIcon
                        :class="open ? 'rotate-180 transform' : ''"
                        class="h-5 w-5 text-dark"
                    />
                </DisclosureButton>
                <DisclosurePanel class="px-4 pb-2 pt-4 text-sm text-gray-500">
                    <div
                        class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                    >
                        <div v-for="category in categories" :key="category.id">
                            <Link
                                :href="
                                    route('kalakalkoto.showCategory', {
                                        category_name: category.name,
                                    })
                                "
                                class="flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"
                            >
                                <span
                                    class="text-sm font-medium text-gray-900 dark:text-white"
                                    >{{ category.name }}</span
                                >
                            </Link>
                        </div>
                    </div>
                </DisclosurePanel>
            </Disclosure>
        </div>
    </div>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronUpIcon } from "@heroicons/vue/20/solid";
import { Link } from "@inertiajs/vue3";

import { ref } from "vue";

const props = defineProps({
    categories: Array,
});
</script>
