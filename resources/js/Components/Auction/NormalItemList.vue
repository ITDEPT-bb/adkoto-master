<template>
	<div>
		<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
			<NormalItemCard
				v-for="item in items.data"
				:key="item.id"
				:item="item" />
		</div>
		<div class="mt-4">
			<Pagination :links="items.links" />
		</div>
	</div>
</template>

<script setup>
import { defineProps } from "vue";
import NormalItemCard from "./NormalItemCard.vue";
import Pagination from "@/Components/Kalakalkoto/Pagination.vue";

const props = defineProps({
	items: Object,
});
</script>

<style scoped></style>
