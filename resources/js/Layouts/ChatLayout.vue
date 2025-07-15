<script setup>
import { onMounted, ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import { MoonIcon } from "@heroicons/vue/24/solid";
import logo from "/public/img/tribekoto.png";
import axiosClient from "@/axiosClient.js";

// import tribekotoIcon from '/public/img/icons/tribekotoicon.png';
// import adkotoIcon from '/public/img/icons/adkotoicon.png';
// import kalakalkotoIcon from '/public/img/icons/kalakalkotoicon.png';
import tribekotoIcon from "/public/img/icons/tribekoto-bk.png";
import adkotoIcon from "/public/img/icons/adkoto-bk.png";
import kalakalkotoIcon from "/public/img/icons/kalakalkoto-bk.png";
import gameIcon from "/public/img/icons/gameicon-bk.png";
import messageIcon from "/public/img/icons/message-bk.png";
import notificationIcon from "/public/img/icons/notification-bk.png";
import NotificationDropdown from "@/Components/Notification/NotificationDropdown.vue";
import MessageIcon from "@/Components/Chat/MessageIconDropdown.vue";

// Valentines Specific
import Cupid from "/public/img/admoto/CUPID.jpg";
import TeddyBear from "/public/img/admoto/TEDDYBEAR.png";
import Ribbon from "/public/img/admoto/RIBBON.png";
import HeartSearch from "/public/img/admoto/HEART HEART.png";

// Mother's Day Specific
import MothersDayIcon from "/public/img/mothers_day/2.png";
import IconBackground from "/public/img/mothers_day/icon_bg.png";
import AwardIcon from "/public/img/mothers_day/best_mom.png";
import SearchBarIcon from "/public/img/mothers_day/icon.png";
import ProfileSmallIcon from "/public/img/mothers_day/p1.png";
import DarkModeToggle from "@/Components/DarkModeToggle.vue";
import IncomingCallModal from "@/Components/Call/IncomingCallModal.vue";

const showingNavigationDropdown = ref(false);
const keywords = ref(usePage().props.search || "");

const authUser = usePage().props.auth.user;

function search() {
    router.get(route("search", encodeURIComponent(keywords.value)));
}

function toggleDarkMode() {
    const html = window.document.documentElement;
    if (html.classList.contains("dark")) {
        html.classList.remove("dark");
        localStorage.setItem("darkMode", "0");
    } else {
        html.classList.add("dark");
        localStorage.setItem("darkMode", "1");
    }
}

const beforeEnter = (el) => {
    el.style.transform = "translateX(100%)";
};
const enter = (el, done) => {
    el.offsetHeight;
    el.style.transition = "transform 0.3s ease-in-out";
    el.style.transform = "translateX(0)";
    done();
};
const leave = (el, done) => {
    el.style.transition = "transform 0.3s ease-in-out";
    el.style.transform = "translateX(100%)";
    setTimeout(() => {
        done();
    }, 300);
};

let logoSrc = "/img/chatkoto.png";
// let logoClass =
//     "block h-6 sm:h-8 xl:h-10 2xl:h-12 w-auto ml-2 sm:ml-4 md:ml-6 lg:ml-2 xl:ml-7 2xl:ml-[70px] hover:scale-110 transition duration-200 ease-in-out scale-75";
let logoText = "Where Connections and Communities Thrive!";
let logoTextClass =
    "absolute bottom-1.5 italic text-red-500 font-black ml-10 left-1/2 transform -translate-x-1/2 translate-y-full text-center text-xs my-1 pt-1";

// Incoming Call Modal
const incomingCall = ref(false);
const incomingCaller = ref(null);
const userToCall = ref(null);
const showModal = ref(false);

onMounted(() => {
    window.Echo.join("agora-online-channel").listen(
        ".MakeAgoraCall",
        async ({ data }) => {
            if (parseInt(data.userToCall) === parseInt(authUser.id)) {
                try {
                    const response = await axiosClient.get(
                        `/fetch-call/${data.from}`
                    );
                    incomingCaller.value = response.data.user;
                    userToCall.value = data.userToCall;
                    showModal.value = true;
                } catch (error) {
                    console.error("Failed to fetch caller info", error);
                }
            }
        }
    );
});
</script>

<template>
    <div
        class="h-full mx-auto overflow-hidden flex flex-col bg-gray-100 dark:bg-gray-800"
    >
        <nav
            class="bg-white w-full dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 fixed md:static top-0 z-10 pt-6 sm:pt-0 shadow"
        >
            <!-- Primary Navigation Menu -->

            <!-- <div
                class="max-w-screen mx-auto px-4 flex items-center justify-between h-16"
            > -->
            <div class="max-w-screen mx-auto px-4 flex items-center justify-between h-16">
                <!-- Logo and Title -->
                <div class="flex items-center space-x-4">
                    <img
                        :src="logoSrc"
                        alt="Logo"
                        class="h-12 w-auto object-contain"
                    />
                    <span
                        class="text-lg font-bold text-gray-700 hidden sm:block"
                    >
                        Where Connections and Communities Thrive!
                    </span>
                </div>

                <!-- Icons and User Menu -->
                <div class="flex items-center space-x-4">
                    <Link
                        :href="route('dashboard')"
                        data-tooltip-target="tribekoto-tooltip"
                        class="bg-white p-0.5 rounded-full hidden sm:flex hover:bg-red-500 hover:text-white hover:border-red-700 hover:shadow-md hover:scale-105 transition duration-200 ease-in-out"
                    >
                        <!-- Tribekoto -->
                        <img
                            :src="tribekotoIcon"
                            class="hidden 2xl:flex h-auto md:w-12 xl:w-10"
                            alt="Logo"
                        />
                    </Link>
                    <button
                        @click="toggleDarkMode"
                        class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700"
                    >
                        <MoonIcon
                            class="h-6 w-6 text-gray-700 dark:text-gray-200"
                        />
                    </button>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <transition
                name="slide"
                @before-enter="beforeEnter"
                @enter="enter"
                @leave="leave"
            >
                <div
                    v-if="showingNavigationDropdown"
                    :class="[
                        'fixed top-0 right-0 z-40 h-screen p-4 pt-8 overflow-y-auto bg-white w-60 dark:bg-gray-800 shadow-2xl scrollbar-thin',
                        {
                            'translate-x-0': showingNavigationDropdown,
                            hidden: !showingNavigationDropdown,
                        },
                    ]"
                    tabindex="-1"
                    id="drawer-right-example"
                    aria-labelledby="drawer-right-label"
                >
                    <!-- Close Button -->
                    <button
                        type="button"
                        @click="showingNavigationDropdown = false"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 right-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white"
                    >
                        <svg
                            class="w-3 h-3 mt-5"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 14 14"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                            />
                        </svg>
                        <span class="sr-only">Close menu</span>
                    </button>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1">
                        <template v-if="authUser">
                            <div
                                class="border-b border-gray-200 dark:border-gray-600 py-4 truncate"
                            >
                                <div
                                    class="flex flex-col items-center justify-center"
                                >
                                    <img
                                        class="h-10 w-10 rounded-full object-cover"
                                        :src="authUser.avatar_url"
                                        :alt="`${authUser.name} ${authUser.surname}`"
                                    />

                                    <div
                                        class="font-semibold text-lg text-gray-800 dark:text-gray-200"
                                    >
                                        <!-- {{ authUser.name }}
                                        {{ authUser.surname }} -->
                                        <p class="truncate w-3/4 mx-auto">
                                            {{ authUser.name }}
                                            {{ authUser.surname }}
                                        </p>
                                    </div>

                                    <div
                                        class="font-medium text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ authUser.email }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink :href="route('dashboard')">
                                    Tribekoto
                                </ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('kalakalkoto')">
                                    Kalakalkoto
                                </ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('adkoto')">
                                    Adkoto
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    :href="
                                        route(
                                            'notifications.fetchAllNotifications'
                                        )
                                    "
                                >
                                    Notifications
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    :href="
                                        route('profile', {
                                            username: authUser.username,
                                        })
                                    "
                                >
                                    Profile
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                >
                                    Log Out
                                </ResponsiveNavLink>
                            </div>
                        </template>
                        <template> Login button </template>
                    </div>
                </div>
            </transition>
        </nav>

        <!-- Page Heading -->
        <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-hidden">
            <slot />
        </main>
    </div>

    <IncomingCallModal v-model="showModal" :user="incomingCaller" />
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease-in-out;
}
.slide-enter,
.slide-leave-to {
    transform: translateX(100%);
}
</style>
