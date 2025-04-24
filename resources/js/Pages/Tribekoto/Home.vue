<script setup>
import { Head, Link } from "@inertiajs/vue3";
import GroupList from "@/Components/Tribekoto/GroupList.vue";
import FollowingList from "@/Components/Tribekoto/FollowingList.vue";
import CreatePost from "@/Components/Tribekoto/CreatePost.vue";
import PostList from "@/Components/Tribekoto/PostList.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import WelcomeOverlay from "@/Components/Tribekoto/WelcomeOverlay.vue";

defineProps({
    posts: Object,
    groups: Array,
    followings: Array,
    suggestedUsers: Array,
    suggestedGroups: Array,
});
</script>

<template>
    <WelcomeOverlay />

    <Head title="Social Media Website" />

    <AuthenticatedLayout>
        <div
            class="grid 3xl:max-w-7xl mx-auto lg:grid-cols-12 gap-3 px-4 h-full scrollbar-thin"
        >
            <div
                class="lg:col-span-3 min-w-2xl lg:order-3 h-full overflow-hidden"
            >
                <GroupList
                    :groups="groups"
                    :suggestedGroups="suggestedGroups"
                />
            </div>
            <div class="lg:col-span-3 lg:order-1 h-full overflow-hidden">
                <FollowingList
                    :users="followings"
                    :suggestedUsers="suggestedUsers"
                />
            </div>
            <div
                class="lg:col-span-6 max-w-3xl mx-auto lg:order-2 h-full overflow-hidden flex flex-col"
            >
                <CreatePost />
                <PostList :posts="posts.data" class="flex-1" />
            </div>
        </div>
    </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<style></style>
