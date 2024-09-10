<template>
    <div class="w-full bg-white shadow rounded-lg mb-2">
        <!-- Mobile Dropdown for Tabs -->
        <div class="sm:hidden">
            <label for="tabs" class="sr-only">Section</label>
            <select
                id="tabs"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                @change="changeRoute($event)"
            >
                <option
                    value="kalakalkoto"
                    :selected="route().current('kalakalkoto')"
                >
                    Kalakalkoto
                </option>
                <option value="auction" :selected="route().current('auction')">
                    Auction
                </option>
            </select>
        </div>

        <!-- Desktop Tabs -->
        <ul
            class="hidden text-sm font-semibold text-center text-gray-500 rounded-lg shadow sm:flex dark:divide-gray-700 dark:text-gray-400"
        >
            <li
                class="me-2 w-full focus-within:z-10 flex justify-center items-center align-middle"
            >
                <a
                    :href="route('kalakalkoto')"
                    class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group"
                    :class="{
                        'text-blue-600 border-blue-600':
                            route().current() === 'kalakalkoto',
                        'text-gray-500': !route().current() === 'kalakalkoto',
                    }"
                >
                    <ShoppingBagIcon
                        class="w-6 h-6 me-1 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300"
                    />
                    <p>Kalakalkoto</p>
                </a>
            </li>

            <li
                class="me-2 w-full focus-within:z-10 flex justify-center items-center align-middle"
            >
                <a
                    href="#"
                    class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group"
                    :class="{
                        'text-blue-600 border-blue-600':
                            route().current('auction'),
                        'text-gray-500': !route().current('auction'),
                    }"
                >
                    <UserGroupIcon
                        class="w-6 h-6 me-1 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300"
                    />Auction
                </a>
            </li>
        </ul>
    </div>
</template>

<script setup>
import ShoppingBagIcon from "./ShoppingBagIcon.vue";
import UserGroupIcon from "./UserGroupIcon.vue";

// Function to change route when the select option changes
const changeRoute = (event) => {
    const selectedRoute = event.target.value;
    router.get(route(selectedRoute));
};
</script>
