<template>
    <Head title="Games" />

    <AuthenticatedLayout>
        <div class="container mx-auto p-6">
            <h1 class="text-3xl font-bold mb-6">Gameskoto</h1>

            <div
                v-if="games.length"
                class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4"
            >
                <div
                    v-for="game in games"
                    :key="game.id"
                    class="relative cursor-pointer"
                >
                    <Link :href="route('game.show', { id: game.id })">
                        <img
                            :src="game.icon"
                            :alt="game.name"
                            class="w-full h-32 object-cover rounded-lg border border-gray-300"
                        />
                        <div
                            class="absolute inset-0 flex items-center justify-center text-white text-lg font-bold rounded-lg"
                        >
                            <p
                                class="bg-black mt-16 bg-opacity-90 py-1 px-4 rounded"
                            >
                                {{ game.name }}
                            </p>
                        </div>
                    </Link>
                </div>
            </div>

            <div v-else>
                <p>No games found.</p>
            </div>
        </div>
    </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { defineProps } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/GameLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

const props = defineProps({
    games: Array,
});
</script>

<style scoped>
/* Optional: Add styles for the game icon overlay */
.relative {
    position: relative;
}
.absolute {
    position: absolute;
}
</style>
