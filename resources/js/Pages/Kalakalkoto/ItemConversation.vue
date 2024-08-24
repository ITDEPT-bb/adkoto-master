<template>
    <Head title="Kalakalkoto Chat" />

    <AuthenticatedLayout>
        <div class="flex h-full overflow-y-auto container p-4 mx-auto">
            <div v-for="item in items" :key="item.id">
                <h2>{{ item.name }}</h2>
                <p>{{ item.description }}</p>
                <p>Conversations: {{ item.conversations.length }}</p>
            </div>
        </div>
    </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { defineProps, ref } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/KalakalLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

const { props } = usePage();
const items = ref(props.items);
</script>
