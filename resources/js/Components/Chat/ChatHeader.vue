<template>
    <div
        class="flex flex-col sm:flex-row sm:items-center justify-between py-3 px-4 rounded-lg border-b-2 border-gray-200 dark:border-none"
        style="background-color: #0076be"
    >
        <div class="flex items-center justify-between sm:mb-0 w-full">
            <!-- Back Button -->
            <Link :href="route('chat.index')" aria-label="Back to chat index">
                <button
                    class="flex items-center justify-center w-6 h-6 p-1 rounded-full bg-gray-200 hover:bg-gray-300"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 text-gray-600"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </Link>

            <!-- User Avatar -->
            <div class="relative mx-2">
                <span class="absolute text-green-500 right-0 bottom-0">
                    <svg width="20" height="20">
                        <circle
                            cx="8"
                            cy="8"
                            r="8"
                            fill="currentColor"
                        ></circle>
                    </svg>
                </span>
                <img
                    :src="user.avatar_url"
                    alt="User avatar"
                    class="w-10 sm:w-12 h-10 sm:h-12 rounded-full"
                />
            </div>

            <!-- User Info & Hamburger -->
            <div class="flex-1 flex items-center justify-between px-2">
                <!-- Name & Username -->
                <div class="flex flex-col">
                    <div
                        class="sm:text-lg text-sm font-semibold text-white truncate"
                    >
                        {{ user.name }} {{ user.surname }}
                    </div>
                    <span class="text-sm font-light text-white hidden sm:block">
                        @{{ user.username }}
                    </span>
                </div>

                <!-- Hamburger (Mobile Only) -->
                <button
                    @click="toggleMenu"
                    class="sm:hidden ml-auto inline-flex items-center justify-center rounded-lg h-10 w-10 transition duration-500 ease-in-out text-white hover:bg-gray-300 hover:text-black focus:outline-none"
                    :aria-expanded="isMenuOpen"
                    aria-label="Toggle menu"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Desktop Call and Profile Buttons -->
        <div class="hidden sm:flex items-center space-x-2">
            <Link
                :href="route('profile', user.username)"
                aria-label="View Profile"
            >
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-white hover:bg-gray-300 hover:text-black focus:outline-none"
                >
                    <ProfileIcon />
                </button>
            </Link>
            <Link
                :href="
                    route('callPage.index', {
                        user: user.id,
                        caller: authUser.id,
                    })
                "
                aria-label="Call User"
            >
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-white hover:bg-gray-300 hover:text-black focus:outline-none"
                >
                    <PhoneIcon />
                </button>
            </Link>
            <!-- <Link
                :href="route('chat.callPage', { userId: user.id })"
                aria-label="Call User"
            >
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-white hover:bg-gray-300 hover:text-black focus:outline-none"
                    @click="showCallModal = true"
                >
                    <PhoneIcon />
                </button>
            </Link> -->
            <!-- <Link :href="route('chat.callPage')" aria-label="Call User">
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-white hover:bg-gray-300 hover:text-black focus:outline-none"
                    @click="showCallModal = true"
                >
                    <PhoneIcon />
                </button>
            </Link> -->
            <!-- <Link :href="route('chat.callPage')" aria-label="Call User">
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-white hover:bg-gray-300 hover:text-black focus:outline-none"
                >
                    <PhoneIcon />
                </button>
            </Link> -->

            <!-- <button
				type="button"
				class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-white hover:bg-gray-300 hover:text-black focus:outline-none"
				@click="showCallModal = true">
				<PhoneIcon />
			</button> -->
            <!-- <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-white hover:bg-gray-300 hover:text-black focus:outline-none"
                @click="startVideoCall"
            >
                <VideoCamera />
            </button> -->
        </div>

        <!-- Dropdown Menu -->
        <div
            v-if="isMenuOpen"
            class="absolute right-3 mt-10 w-40 bg-white rounded-lg shadow-lg z-50 sm:hidden transition-transform duration-300"
            @click.outside="isMenuOpen = false"
        >
            <Link :href="route('profile', user.username)">
                <button
                    class="flex items-center w-full px-4 py-2 text-gray-800 hover:bg-gray-200"
                >
                    <ProfileIcon class="mr-2" /> View Profile
                </button>
            </Link>
            <!-- <Link :href="route('chat.callPage')" aria-label="Call User">
                <button
                    class="flex items-center w-full px-4 py-2 text-gray-800 hover:bg-gray-200"
                    @click="startVoiceCall"
                >
                    <PhoneIcon class="mr-2" /> Voice Call
                </button>
            </Link> -->
            <Link
                :href="
                    route('callPage.index', {
                        user: user.id,
                        caller: authUser.id,
                    })
                "
                aria-label="Call User"
            >
                <button
                    class="flex items-center w-full px-4 py-2 text-gray-800 hover:bg-gray-200"
                >
                    <PhoneIcon class="mr-2" /> Voice Call
                </button>
            </Link>
            <!-- <button
				class="flex items-center w-full px-4 py-2 text-gray-800 hover:bg-gray-200"
				@click="startVideoCall">
				<VideoCamera class="mr-2" /> Video Call
			</button> -->
        </div>
    </div>

    <!-- <CallModalDialog
        :isOpen="isModalOpen"
        @close="closeModal"
        :user="user"
        :authUser="authUser"
        :appId="appId"
    /> -->
</template>

<script setup>
import { ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import ProfileIcon from "@/Components/Icons/ProfileIcon.vue";
import PhoneIcon from "@/Components/Icons/PhoneIcon.vue";
import VideoCamera from "@/Components/Icons/VideoCamera.vue";
import CallModalDialog from "@/Components/Chat/CallModalDialog.vue";
import BankNoteIcon from "../Icons/BankNoteIcon.vue";

const props = defineProps({
    user: Object,
    appId: String,
});

const user = props.user;
const appId = props.appId;
const authUser = usePage().props.auth.user;
const isMenuOpen = ref(false);
const showCallModal = ref(false);

function toggleMenu() {
    isMenuOpen.value = !isMenuOpen.value;
}

const isModalOpen = ref(false);
function startVideoCall() {
    isModalOpen.value = true;
}

function closeModal() {
    isModalOpen.value = false;
}

// function startVoiceCall() {
//     console.log("Starting voice call with", user.username);
// }

// function startVideoCall() {
//     console.log("Starting video call with", user.username);
// }
</script>
