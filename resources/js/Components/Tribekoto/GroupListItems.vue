<script setup>
import TextInput from "@/Components/TextInput.vue";
import GroupItem from "@/Components/Tribekoto/GroupItem.vue";
import { ref } from "vue";
import GroupModal from "@/Components/Tribekoto/GroupModal.vue";

import { PlusIcon } from "@heroicons/vue/24/solid";

const searchKeyword = ref("");
const showNewGroupModal = ref(false);

const props = defineProps({
	groups: Array,
});

function onGroupCreate(group) {
	props.groups.unshift(group);
}
</script>

<template>
	<!-- <TextInput :model-value="searchKeyword" placeholder="Type to search" class="w-full mt-4" /> -->
	<div class="flex gap-2 mt-4">
		<TextInput
			:model-value="searchKeyword"
			placeholder="Type to search"
			class="w-full" />
		<button
			@click="showNewGroupModal = true"
			class="text-center items-center bg-red-500 hover:bg-red-600 text-white rounded py-1 px-2">
			<!-- new group -->
			<PlusIcon class="h-8 w-8" />
		</button>
	</div>

	<div class="mt-3 h-[300px] lg:flex-1 overflow-auto scrollbar-thin">
		<div
			v-if="false"
			class="text-gray-400 text-center p-3">
			You are not joined to any groups
		</div>
		<div v-else>
			<GroupItem
				v-for="group of groups"
				:group="group" />
		</div>
	</div>

	<GroupModal
		v-model="showNewGroupModal"
		@create="onGroupCreate" />
</template>

<style scoped></style>
