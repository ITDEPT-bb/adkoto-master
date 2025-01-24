<template>
	<Head title="Kalakalkoto" />

	<AuthenticatedLayout>
		<div class="max-w-7xl mx-auto h-full overflow-y-auto p-4 scrollbar-thin">
			<KalakalMenu />
			<ProductList :kalakalitems="kalakalitems" />
		</div>
	</AuthenticatedLayout>

	<UpdateProfileReminder />
</template>

<script setup>
import { ref } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

import ProductList from "@/Components/Kalakalkoto/ProductList.vue";
import KalakalMenu from "@/Components/Kalakalkoto/KalakalMenu.vue";

const { props } = usePage();
const kalakalitems = ref(props.kalakalitems);
</script>
