<template>
    <div
        class="col-span-1 lg:col-span-3 h-full overflow-y-auto bg-white p-4 rounded-lg shadow-md"
    >
        <h2 class="text-xl font-bold mb-4">Following</h2>
        <ul class="space-y-2">
            <FollowingListItem
                v-for="user in followings"
                :key="user.id"
                :user="user"
                @click="handleClick(user)"
            />
        </ul>
    </div>
</template>

<script setup>
import FollowingListItem from "@/Components/Chat/FollowingListItem.vue";
import { defineProps, useContext } from "vue";

const props = defineProps({
    followings: Array,
});

const { emit } = useContext();

const handleClick = (user) => {
    emit("selectUser", user);
};
</script>
