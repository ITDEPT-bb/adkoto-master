<template>
    <div
        class="col-span-1 lg:col-span-3 h-full overflow-y-auto bg-white p-4 rounded-lg shadow-md"
    >
        <h2 class="text-xl font-bold mb-4">User Info</h2>
        <div v-if="selectedUser" class="flex items-center space-x-4">
            <img
                class="w-10 h-10 rounded-full object-cover"
                :src="selectedUser.avatar_url"
                alt="User Image"
            />
            <div>
                <h3 class="text-lg font-semibold">
                    {{ selectedUser.name }} {{ selectedUser.surname }}
                </h3>
            </div>
        </div>
        <div v-else class="text-gray-500">
            Select a user to view their information.
        </div>
    </div>
</template>

<script setup>
import { defineProps, useContext } from "vue";

const props = defineProps({
    selectedUser: Object,
});
</script>
