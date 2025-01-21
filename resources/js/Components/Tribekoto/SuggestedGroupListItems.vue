<script setup>
import GroupItem from "@/Components/Tribekoto/GroupItem.vue";
import { defineProps } from "vue";

const props = defineProps({
	groups: Array,
});
</script>

<template>
	<div class="mt-3 h-[300px] lg:flex-1 overflow-auto scrollbar-thin">
		<div
			v-if="groups.length === 0"
			class="text-gray-400 text-center p-3">
			No suggested groups available
		</div>
		<div v-else>
			<GroupItem
				v-for="group in groups"
				:key="group.id"
				:group="group" />
		</div>
	</div>
</template>

<style scoped></style>
