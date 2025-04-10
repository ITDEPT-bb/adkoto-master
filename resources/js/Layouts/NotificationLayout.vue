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
import tribekotoIcon from "/public/img/icons/tribekoto-bk.png";
import adkotoIcon from "/public/img/icons/adkoto-bk.png";
import kalakalkotoIcon from "/public/img/icons/kalakalkoto-bk.png";
import gameIcon from "/public/img/icons/gameicon-bk.png";
import messageIcon from "/public/img/icons/message-bk.png";
// import notificationIcon from "/public/img/icons/notification.png";
import NotificationDropdown from "@/Components/Notification/NotificationDropdown.vue";
import MessageIcon from "@/Components/Chat/MessageIconDropdown.vue";

// Valentines Specific
import Cupid from "/public/img/admoto/CUPID.jpg";
import TeddyBear from "/public/img/admoto/TEDDYBEAR.png";
import Ribbon from "/public/img/admoto/RIBBON.png";
import HeartSearch from "/public/img/admoto/HEART HEART.png";

// Day of Valor Specific
import DayOfValorBg from "/public/img/Kagitingan/kagitingan_bg.jpg";
import DayOfValorPeople from "/public/img/Kagitingan/kagitingan_people.png";

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

let logoSrc = "/img/tribekoto.png";
let logoClass =
    "block h-6 sm:h-8 xl:h-10 2xl:h-12 w-auto ml-2 sm:ml-4 md:ml-6 lg:ml-2 xl:ml-7 2xl:ml-[70px] hover:scale-110 transition duration-200 ease-in-out scale-75";
let logoText = "Where Connections and Communities Thrive!";
let logoTextClass =
    "absolute bottom-1.5 italic text-red-500 font-black ml-10 left-1/2 transform -translate-x-1/2 translate-y-full text-center text-xs my-1 pt-1";
</script>

<template>
    <div
        class="h-full mx-auto overflow-hidden flex flex-col bg-gray-100 dark:bg-gray-800"
    >
        <!-- <div class="h-full mx-auto overflow-hidden flex flex-col"> -->
        <nav
            class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 fixed md:static top-0 z-10 pt-6 sm:pt-0"
        >
            <!-- Primary Navigation Menu -->

            <div class="max-w-screen mx-auto px-4 sm:px-6 lg:px-16">
                <!-- <div class="flex items-center justify-between gap-4 h-16">
                    <div class="flex"> -->
                <div class="flex items-center justify-between gap-2 h-16">
                    <!-- <div
                        class="absolute -bottom-0.5 left-1 xl:left-6 2xl:left-12 lg:block hidden"
                    >
                        <img
                            :src="DayOfValorPeople"
                            class="w-16 relative -top-0.5 left-0 h-auto"
                            alt="Logo"
                        />
                    </div> -->
                    <div class="flex sm:mr-2">
                        <!-- Logo -->
                        <!-- <div class="shrink-0 flex items-center bg-white bg-opacity-75 px-4 py-2 rounded-full"> -->

                        <!-- <div class="shrink-0 flex items-center">
							<Link :href="route('dashboard')">
								<img
									:src="logo"
									class="block h-6 sm:h-8 xl:h-10 2xl:h-12 w-auto ml-2 sm:ml-4 md:ml-6 lg:ml-2 xl:ml-7 2xl:ml-[70px] hover:scale-110 transition duration-200 ease-in-out"
									alt="Logo" />
							</Link>
						</div> -->
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')">
                                <div
                                    class="relative group mb-0 md:mb-3 bg-white bg-opacity-70 py-1 rounded-full sm:bg-none sm:bg-opacity-0 sm:px-0 sm:py-0"
                                >
                                    <img
                                        :src="logoSrc"
                                        :class="logoClass"
                                        alt="Logo"
                                        class="transition-transform duration-200 ease-in-out transform group-hover:scale-90"
                                    />
                                    <div
                                        :class="logoTextClass"
                                        class="transition-transform duration-200 hidden md:block ease-in-out transform group-hover:scale-110"
                                        style="white-space: nowrap"
                                    >
                                        {{ logoText }}
                                    </div>
                                </div>
                            </Link>
                        </div>

                        <!-- <div
							class="shrink-0 flex items-center bg-white bg-opacity-20 px-2 py-1 rounded-lg"
							style="
								background: url('/img/adkoto_halloween_cloud.png') center/cover no-repeat;
								background-size: cover;
								min-width: 100%;
								min-height: 100%;
							">
							<Link :href="route('dashboard')">
								<img
									:src="logo"
									class="block h-7 sm:h-9 w-auto"
									alt="Logo" />
							</Link>
						</div> -->
                    </div>

                    <!-- <div class="hidden sm:flex sm:items-center sm:ms-6"> -->
                    <!-- <div class="flex-1"> -->
                    <div class="flex items-center sm:gap-9 p-4 flex-1">
                        <!-- <TextInput
							v-model="keywords"
							placeholder="Search....."
							class="w-32 sm:w-1/2"
							@keyup.enter="search" /> -->
                        <div
                            class="relative w-48 sm:w-1/3 ps-16 sm:ps-0 -right-8"
                        >
                            <TextInput
                                v-model="keywords"
                                placeholder="Search..."
                                class="w-full pr-10 pl-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                @keyup.enter="search"
                            />
                            <!-- <img
                                :src="HeartSearch"
                                alt="Search"
                                class="absolute right-3 hidden sm:block top-1/2 transform -translate-y-1/2 w-20 h-auto text-gray-400"
                            /> -->
                        </div>

                        <div
                            class="justify-end items-center flex flex-row sm:gap-9 p-4 flex-1"
                        >
                            <Link
                                :href="route('dashboard')"
                                data-tooltip-target="tribekoto-tooltip"
                                class="bg-white p-0.5 rounded-full hidden sm:flex hover:bg-red-500 hover:text-white hover:border-red-700 hover:shadow-md hover:scale-105 transition duration-200 ease-in-out"
                            >
                                <img
                                    :src="tribekotoIcon"
                                    class="hidden sm:flex h-auto md:w-12 xl:w-10"
                                    alt="Logo"
                                />
                            </Link>
                            <div
                                id="tribekoto-tooltip"
                                role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-blue-500 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                            >
                                Tribekoto
                                <div
                                    class="tooltip-arrow"
                                    data-popper-arrow
                                ></div>
                            </div>

                            <Link
                                :href="route('kalakalkoto')"
                                data-tooltip-target="kalakalkoto-tooltip"
                                class="bg-white p-0.5 rounded-full hidden sm:flex hover:bg-red-500 hover:text-white hover:border-red-700 hover:shadow-md hover:scale-105 transition duration-200 ease-in-out"
                            >
                                <img
                                    :src="kalakalkotoIcon"
                                    class="hidden sm:flex h-auto md:w-12 xl:w-10"
                                    alt="Logo"
                                />
                            </Link>
                            <div
                                id="kalakalkoto-tooltip"
                                role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-blue-500 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                            >
                                Kalakalkoto
                                <div
                                    class="tooltip-arrow"
                                    data-popper-arrow
                                ></div>
                            </div>

                            <Link
                                :href="route('adkoto')"
                                data-tooltip-target="adkoto-tooltip"
                                class="bg-white p-0.5 rounded-full hidden sm:flex hover:bg-red-500 hover:text-white hover:border-red-700 hover:shadow-md hover:scale-105 transition duration-200 ease-in-out"
                            >
                                <img
                                    :src="adkotoIcon"
                                    class="hidden sm:flex md:h-auto md:w-12 xl:w-10"
                                    alt="Logo"
                                />
                            </Link>
                            <div
                                id="adkoto-tooltip"
                                role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-blue-500 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                            >
                                Adkoto
                                <div
                                    class="tooltip-arrow"
                                    data-popper-arrow
                                ></div>
                            </div>

                            <Link
                                :href="route('games.index')"
                                data-tooltip-target="games-tooltip"
                                class="bg-white p-0.5 rounded-full hidden sm:flex hover:bg-red-500 hover:text-white hover:border-red-700 hover:shadow-md hover:scale-105 transition duration-200 ease-in-out"
                            >
                                <img
                                    :src="gameIcon"
                                    class="hidden sm:flex md:h-auto md:w-12 xl:w-10"
                                    alt="Logo"
                                />
                            </Link>
                            <div
                                id="games-tooltip"
                                role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-blue-500 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                            >
                                Games
                                <div
                                    class="tooltip-arrow"
                                    data-popper-arrow
                                ></div>
                            </div>

                            <!-- <Link
							:href="route('chat.index')"
							class="bg-white p-0.5 rounded-full hidden sm:flex">
							<img
								:src="messageIcon"
								class="hidden sm:flex md:h-auto md:w-12 xl:w-10"
								alt="Logo" />
						</Link> -->
                            <!-- <Link
							:href="route('kalakalkoto')"
							class="rounded-full hidden sm:flex">
							<img
								:src="kalakalkotoIcon"
								class="hidden sm:flex h-auto md:w-12 xl:w-[58px]"
								alt="Logo" />
						</Link>

						<Link
							:href="route('adkoto')"
							class="rounded-full hidden sm:flex">
							<img
								:src="adkotoIcon"
								class="hidden sm:flex md:h-auto md:w-12 xl:w-[58px]"
								alt="Logo" />
						</Link>

						<Link
							:href="route('games.index')"
							class="rounded-full hidden sm:flex">
							<img
								:src="gameIcon"
								class="hidden sm:flex md:h-auto md:w-12 xl:w-[58px]"
								alt="Logo" />
						</Link> -->

                            <!-- <Link
							:href="route('chat.index')"
							class="rounded-full hidden sm:flex">
							<img
								:src="messageIcon"
								class="hidden sm:flex md:h-auto md:w-12 xl:w-[58px]"
								alt="Logo" />
						</Link> -->

                            <MessageIcon
                                data-tooltip-target="message-tooltip"
                                class="hidden sm:flex rounded-full"
                            />
                            <div
                                id="message-tooltip"
                                role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-blue-500 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                            >
                                Message
                                <div
                                    class="tooltip-arrow"
                                    data-popper-arrow
                                ></div>
                            </div>

                            <!-- Notification Dropdown Component -->
                            <!-- <NotificationDropdown class="hidden sm:flex bg-white p-0.5 rounded-full" /> -->
                            <NotificationDropdown
                                data-tooltip-target="notifications-tooltip"
                                class="hidden sm:flex rounded-full border border-red-500"
                            />
                            <div
                                id="notifications-tooltip"
                                role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-blue-500 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                            >
                                Notifications
                                <div
                                    class="tooltip-arrow"
                                    data-popper-arrow
                                ></div>
                            </div>
                        </div>
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
                                            data-tooltip-target="profile-tooltip"
                                            class="inline-flex items-center bg-white p-0.5 rounded-full border border-transparent text-sm leading-4 font-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none hover:bg-red-500 hover:shadow-md hover:scale-110 transition ease-in-out duration-150"
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
                                            <!-- <div
                                                class="absolute -bottom-1 xl:right-2 2xl:right-2 lg:block hidden"
                                            >
                                                <img
                                                    :src="Ribbon"
                                                    class="w-5 relative top-0 right-0 h-auto"
                                                    alt="Logo"
                                                />
                                            </div> -->
                                        </button>
                                        <div
                                            id="profile-tooltip"
                                            role="tooltip"
                                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-blue-500 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                                        >
                                            Profile
                                            <div
                                                class="tooltip-arrow"
                                                data-popper-arrow
                                            ></div>
                                        </div>
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

                    <!-- <div
                        class="absolute -bottom-5 xl:right-1 2xl:right-2 lg:block hidden"
                    >
                        <img
                            :src="TeddyBear"
                            class="w-16 relative top-0 right-0 h-auto"
                            alt="Logo"
                        />
                    </div> -->

                    <!-- Hamburger -->
                    <!-- <div class="-me-2 flex items-center sm:hidden">
						<button
							@click="showingNavigationDropdown = !showingNavigationDropdown"
							class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
							<svg
								class="h-6 w-6"
								stroke="currentColor"
								fill="none"
								viewBox="0 0 24 24">
								<path
									:class="{
										hidden: showingNavigationDropdown,
										'inline-flex': !showingNavigationDropdown,
									}"
									stroke-linecap="round"
									stroke-linejoin="round"
									stroke-width="2"
									d="M4 6h16M4 12h16M4 18h16" />
								<path
									:class="{
										hidden: !showingNavigationDropdown,
										'inline-flex': showingNavigationDropdown,
									}"
									stroke-linecap="round"
									stroke-linejoin="round"
									stroke-width="2"
									d="M6 18L18 6M6 6l12 12" />
							</svg>
						</button>
					</div> -->
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
            <!-- <div
				:class="{
					block: showingNavigationDropdown,
					hidden: !showingNavigationDropdown,
				}"
				class="sm
                :hidden"> -->
            <!-- Responsive Settings Options -->
            <!-- <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
					<template v-if="authUser">
						<div class="px-4">
							<div class="font-medium text-base text-gray-800 dark:text-gray-200">
								{{ authUser.name }}
							</div>
							<div class="font-medium text-sm text-gray-500">
								{{ authUser.email }}
							</div>
						</div>

						<div class="mt-3 space-y-1">
							<ResponsiveNavLink :href="route('chat.index')"> Chat </ResponsiveNavLink>
							<ResponsiveNavLink :href="route('kalakalkoto')"> Kalakalkoto </ResponsiveNavLink>
							<ResponsiveNavLink :href="route('adkoto')"> Adkoto </ResponsiveNavLink>
							<ResponsiveNavLink :href="route('notifications.fetchAllNotifications')">
								Notifications
							</ResponsiveNavLink>
							<ResponsiveNavLink
								:href="
									route('profile', {
										username: authUser.username,
									})
								">
								Profile
							</ResponsiveNavLink>
							<ResponsiveNavLink
								:href="route('logout')"
								method="post"
								as="button">
								Log Out
							</ResponsiveNavLink>
						</div>
					</template>
					<template> Login button </template>
				</div>
			</div> -->
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
                                        {{ authUser.name }}
                                        {{ authUser.surname }}
                                    </div>

                                    <div
                                        class="font-medium text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ authUser.email }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink :href="route('kalakalkoto')">
                                    Kalakalkoto
                                </ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('adkoto')">
                                    Adkoto
                                </ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('chat.index')">
                                    Chat
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
