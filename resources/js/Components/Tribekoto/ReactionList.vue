<template>
    <div>
        <!-- Button to open reactions modal with loading indicator -->
        <button
            @click="fetchReactions"
            class="text-sm text-blue-500 hover:underline pb-2 flex items-center gap-2"
            :disabled="isLoadingReactions"
        >
            <!-- Show spinner when fetching reactions -->
            <span v-if="isLoadingReactions" class="flex items-center gap-1">
                <svg
                    class="animate-spin h-4 w-4 text-blue-500"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                    ></path>
                </svg>
                Loading...
            </span>
            <!-- Show button text when not loading -->
            <span v-else> Reactions </span>
        </button>

        <!-- Reactions Modal -->
        <div
            v-if="showReactionsModal"
            class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50"
        >
            <div class="bg-white p-4 rounded-lg max-w-lg w-full">
                <h2 class="text-lg font-semibold mb-4">Reactions</h2>

                <!-- Show error message if reactions fail to load -->
                <p v-if="reactionsError" class="text-red-500">
                    Failed to load reactions. Please try again.
                </p>

                <!-- Reactions List -->
                <ul
                    v-else
                    class="max-w-md divide-y divide-gray-200 dark:divide-gray-700"
                >
                    <li
                        v-for="reaction in reactions"
                        :key="reaction.user.id"
                        class="pb-3 sm:pb-4"
                    >
                        <div
                            class="flex items-center space-x-4 rtl:space-x-reverse border p-2 rounded-lg"
                        >
                            <div class="flex-shrink-0">
                                <Link
                                    :href="
                                        route('profile', reaction.user.username)
                                    "
                                >
                                    <img
                                        class="w-8 h-8 rounded-full"
                                        :src="
                                            reaction.user.avatar_url ||
                                            '/img/default_avatar.png'
                                        "
                                        alt="User avatar"
                                    />
                                </Link>
                            </div>
                            <div class="flex-1 min-w-0">
                                <Link
                                    :href="
                                        route('profile', reaction.user.username)
                                    "
                                >
                                    <p
                                        class="text-sm font-medium text-gray-900 truncate dark:text-white hover:font-semibold hover:underline"
                                    >
                                        {{ reaction.user.name }}
                                        {{ reaction.user.surname }}
                                    </p>
                                </Link>
                                <p
                                    class="text-xs text-gray-500 truncate dark:text-gray-400"
                                >
                                    @{{ reaction.user.username }}
                                </p>
                            </div>
                            <div
                                class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white"
                            >
                                <span>
                                    {{ getReactionIcon(reaction.type) }}
                                    {{ reaction.type }}
                                </span>
                            </div>
                        </div>
                    </li>
                </ul>

                <!-- Close Button -->
                <button
                    @click="showReactionsModal = false"
                    class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-cyan-200 dark:focus:ring-cyan-800"
                >
                    <span
                        class="relative px-5 py-1.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0"
                    >
                        Close
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axiosClient from "@/axiosClient.js";
import { usePage, Link } from "@inertiajs/vue3";

const props = defineProps({
    postId: {
        type: Number,
        required: true,
    },
});

const isLoadingReactions = ref(false);
const showReactionsModal = ref(false);
const reactions = ref([]);
const reactionsError = ref(false);

const fetchReactions = async () => {
    isLoadingReactions.value = true;
    reactionsError.value = false;
    try {
        const response = await axiosClient.get(
            `/post/${props.postId}/reactions`
        );
        reactions.value = response.data;
        showReactionsModal.value = true;
    } catch (error) {
        console.error("Error fetching reactions:", error);
        reactionsError.value = true;
    } finally {
        isLoadingReactions.value = false;
    }
};

const getReactionIcon = (reactionType) => {
    switch (reactionType) {
        case "like":
            return "👍";
        case "love":
            return "❤️";
        case "haha":
            return "😂";
        case "wow":
            return "😮";
        case "sad":
            return "😢";
        case "angry":
            return "😡";
        default:
            return "";
    }
};
</script>
