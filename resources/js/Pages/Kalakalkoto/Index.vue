<template>
	<Head title="Kalakalkoto" />

	<KalakalLayout>
		<div class="max-w-7xl mx-auto h-full overflow-y-auto p-4 scrollbar-thin">
			<PageSelector />
			<KalakalMenu />
			<CategoryMenu :categories="categories" />
			<ProductList :kalakalitems="kalakalitems" />
		</div>
	</KalakalLayout>

	<UpdateProfileReminder />
</template>

<script setup>
import { ref } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import KalakalLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

import CategoryMenu from "@/Components/Kalakalkoto/CategoryMenu.vue";
import ProductList from "@/Components/Kalakalkoto/ProductList.vue";
import KalakalMenu from "@/Components/Kalakalkoto/KalakalMenu.vue";
import PageSelector from "@/Components/Kalakalkoto/PageSelector.vue";

const { props } = usePage();
const kalakalitems = ref(props.kalakalitems);
const categories = ref(props.categories);
</script>
