<template>
	<div class="w-full bg-white shadow rounded-lg">
		<div class="mx-auto w-full rounded-2xl bg-white p-2">
			<Disclosure v-slot="{ open }">
				<DisclosureButton
					class="flex w-full justify-between rounded-lg px-4 py-2 text-left text-lg font-bold text-dark hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500/75">
					<span>Advertisement Categories</span>
					<ChevronUpIcon
						:class="open ? 'rotate-180 transform' : ''"
						class="h-5 w-5 text-dark" />
				</DisclosureButton>
				<DisclosurePanel class="px-4 pb-2 pt-4 text-sm text-gray-500">
					<div class="grid lg:grid-cols-4 grid-cols-2">
						<div
							v-for="category in categories"
							:key="category.id"
							class="p-4">
							<Link
								:href="
									route('adkoto.showCategory', {
										category_name: category.name,
									})
								">
								<h2 class="text-md font-semibold mb-2 text-blue-500 hover:underline">
									{{ category.name }} ({{ category.advertisements_count }})
								</h2>
							</Link>
							<ul class="pl-5 list-disc">
								<li
									v-for="subCategory in category.sub_categories"
									:key="subCategory.id"
									class="text-gray-700 text-xs pb-1 hover:underline">
									<Link
										:href="
											route('adkoto.showSubcategory', {
												category_name: category.name,
												subcategory_name: subCategory.name,
											})
										">
										<span> {{ subCategory.name }} ({{ subCategory.advertisements_count }}) </span>
									</Link>
								</li>
							</ul>
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
