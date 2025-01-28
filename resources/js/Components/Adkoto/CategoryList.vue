<template>
	<aside class="shadow rounded-lg bg-white">
		<section class="p-4">
			<h1 class="text-3xl font-bold mb-2">Advertisement Categories</h1>
			<div class="grid grid-cols-3">
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
		</section>
	</aside>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
const props = defineProps({
	categories: Array,
});
</script>
