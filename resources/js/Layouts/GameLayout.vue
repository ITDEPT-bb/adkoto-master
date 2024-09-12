<script setup>
import { onMounted, ref, computed } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import { MoonIcon } from "@heroicons/vue/24/solid";
import logo from "/public/img/tribekoto.png";

// import tribekotoIcon from '/public/img/icons/tribekotoicon.png';
// import adkotoIcon from '/public/img/icons/adkotoicon.png';
// import kalakalkotoIcon from '/public/img/icons/kalakalkotoicon.png';
import tribekotoIcon from "/public/img/icons/tribekoto.png";
import adkotoIcon from "/public/img/icons/adkoto.png";
import kalakalkotoIcon from "/public/img/icons/kalakalkoto.png";
import messageIcon from "/public/img/icons/message.png";
// import notificationIcon from "/public/img/icons/notification.png";
import NotificationDropdown from "@/Components/Notification/NotificationDropdown.vue";

import axiosClient from "@/axiosClient.js";

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
</script>

<template>
    <div
        class="h-full mx-auto overflow-hidden flex flex-col bg-gray-100 dark:bg-gray-800"
    >
        <nav
            class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700"
        >
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- <div class="flex items-center justify-between gap-4 h-16">
                    <div class="flex"> -->
                <div class="flex items-center justify-between gap-2 h-16">
                    <div class="flex mr-2">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')">
                                <!-- <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" /> -->
                                <img
                                    :src="logo"
                                    class="block h-7 sm:h-9 w-auto"
                                    alt="Logo"
                                />
                            </Link>
                        </div>
                    </div>

                    <!-- <div class="hidden sm:flex sm:items-center sm:ms-6"> -->
                    <!-- <div class="flex-1"> -->
                    <div class="flex items-center sm:gap-9 p-2 flex-1">
                        <!-- <TextInput
                            v-model="keywords"
                            placeholder="Search on the website"
                            class="w-full sm:w-8/12"
                            @keyup.enter="search"
                        /> -->
                        <TextInput
                            v-model="keywords"
                            placeholder="Search on the website"
                            class="w-32 sm:w-1/2 sm:mr-32"
                            @keyup.enter="search"
                        />

                        <Link :href="route('dashboard')">
                            <!-- Tribekoto -->
                            <img
                                :src="tribekotoIcon"
                                class="hidden sm:flex h-auto md:w-12 xl:w-10"
                                alt="Logo"
                            />
                        </Link>

                        <Link :href="route('kalakalkoto')">
                            <!-- Kalakalkoto -->
                            <img
                                :src="kalakalkotoIcon"
                                class="hidden sm:flex h-auto md:w-12 xl:w-10"
                                alt="Logo"
                            />
                        </Link>

                        <Link :href="route('adkoto')">
                            <!-- Adkoto -->
                            <img
                                :src="adkotoIcon"
                                class="hidden sm:flex h-auto md:w-12 xl:w-10"
                                alt="Logo"
                            />
                        </Link>

                        <Link :href="route('chat.index')">
                            <!-- Chatkoto -->
                            <img
                                :src="messageIcon"
                                class="hidden sm:flex h-auto md:w-12 xl:w-10"
                                alt="Logo"
                            />
                        </Link>

                        <!-- Notification Dropdown Component -->
                        <NotificationDropdown class="hidden sm:flex" />

                        <!-- <button @click="toggleDarkMode" class="dark:text-white">
                            <MoonIcon class="w-5 h-5" />
                        </button> -->
                    </div>

                    <div class="hidden sm:flex sm:items-center">
                        <!-- Settings Dropdown -->
                        <div class="ms-3 relative">
                            <Dropdown v-if="authUser" align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                                        >
                                            <!-- {{ authUser.name }}

                                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg> -->
                                            <img
                                                class="h-8 w-8 rounded-full object-cover"
                                                :src="authUser.avatar_url"
                                                :alt="authUser.name"
                                            />
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink
                                        :href="
                                            route('profile', {
                                                username: authUser.username,
                                            })
                                        "
                                    >
                                        Profile
                                    </DropdownLink>
                                    <DropdownLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                    >
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                            <div v-else>
                                <Link
                                    :href="route('login')"
                                    class="dark:text-gray-100"
                                >
                                    Login
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button
                            @click="
                                showingNavigationDropdown =
                                    !showingNavigationDropdown
                            "
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                        >
                            <svg
                                class="h-6 w-6"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex':
                                            !showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex':
                                            showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div
                :class="{
                    block: showingNavigationDropdown,
                    hidden: !showingNavigationDropdown,
                }"
                class="sm:hidden"
            >
                <!-- Responsive Settings Options -->
                <div
                    class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600"
                >
                    <template v-if="authUser">
                        <div class="px-4">
                            <div
                                class="font-medium text-base text-gray-800 dark:text-gray-200"
                            >
                                {{ authUser.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">
                                {{ authUser.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <!-- <ResponsiveNavLink :href="route('dashboard')">
                                Tribekoto
                            </ResponsiveNavLink> -->
                            <ResponsiveNavLink :href="route('chat.index')">
                                Chat
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('kalakalkoto')">
                                Kalakalkoto
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('adkoto')">
                                Adkoto
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="
                                    route('notifications.fetchAllNotifications')
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
</template>
