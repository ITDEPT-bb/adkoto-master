<template>
    <li
        class="flex items-center space-x-2 cursor-pointer hover:bg-gray-100 p-2 rounded-lg"
        @click="handleClick"
    >
        <img
            class="w-10 h-10 rounded-full object-cover"
            :src="user.avatar_url"
            alt="User Image"
        />
        <span>{{ user.name }} {{ user.surname }}</span>
    </li>
</template>

<script setup>
import { defineProps, useContext } from "vue";

const props = defineProps({
    user: Object,
});

const { emit } = useContext();

const handleClick = () => {
    emit("selectUser", user);
};
</script>
