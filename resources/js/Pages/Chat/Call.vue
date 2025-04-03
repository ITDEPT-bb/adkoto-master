<template>
    <Head title="Conversation" />
    <AuthenticatedLayout>
        <div
            class="flex-1 my-2 pb-6 justify-between max-w-7xl mx-auto bg-white rounded-lg border flex flex-col h-full scrollbar-thin"
        >
            <!-- <CallUser :users="users" :appId="appId" :authuser="authUser" /> -->
            <CallList :users="users" :appId="appId" :authUser="authUser" />
        </div>
    </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/ChatLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import CallUser from "@/Components/Chat/CallUser.vue";
import { usePage, Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axiosClient from "@/axiosClient.js";
import CallList from "@/Components/Chat/CallList.vue";

const props = defineProps({
    users: Object,
    appID: String,
});

const users = props.users;
const appId = props.appID;
const authUser = usePage().props.auth.user;
</script>
