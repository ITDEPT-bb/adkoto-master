<script setup>
import { Head, Link } from "@inertiajs/vue3";
import FollowingList from "@/Components/Chat/FollowingList.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

defineProps({
    followings: Array,
});
</script>

<template>
    <Head title="Chat" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto gap-3 p-4 h-full">
            <div class="h-full overflow-y-auto">
                <FollowingList :users="followings" />
            </div>
        </div>
    </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<style></style>
