<template>
    <div class="border-t mx-4 pt-4 overflow-hidden max-h-full flex flex-col">
        <TabGroup>
            <TabList
                class="flex bg-slate-200 dark:bg-slate-900 dark:text-white flex-wrap mb-10 p-2 items-center"
            >
                <Tab v-slot="{ selected }" as="template">
                    <TabItem text="Conversations" :selected="selected" />
                </Tab>
                <Tab v-slot="{ selected }" as="template">
                    <TabItem text="Group Chats" :selected="selected" />
                </Tab>
                <Tab v-slot="{ selected }" as="template">
                    <TabItem text="Kalakalkoto" :selected="selected" />
                </Tab>
                <Tab v-slot="{ selected }" as="template">
                    <TabItem text="Adkoto" :selected="selected" />
                </Tab>
            </TabList>

            <TabPanels class="flex-1 overflow-y-auto scrollbar-thin">
                <TabPanel>
                    <div
                        class="sticky flex justify-between top-0 bg-white dark:bg-slate-900 shadow-sm py-2 px-4 items-center"
                    >
                        <h2 class="text-xl font-bold dark:text-white">
                            Conversations
                        </h2>
                        <button
                            @click="openSearchModal"
                            class="text-center items-center bg-red-500 hover:bg-red-600 text-white rounded py-1 px-2"
                        >
                            <!-- new group -->
                            <MagnifyingGlassIcon class="h-7 w-7" />
                        </button>
                    </div>
                    <ul v-if="followings.length">
                        <li
                            v-for="following in followings"
                            :key="following.id"
                            class="mb-1 px-3 py-1 border-b border-gray-200"
                        >
                            <Link
                                :href="`/chat/conversation/adktu/${following.id}`"
                            >
                                <!-- <div
                                    class="flex items-center space-x-4 transition-transform duration-300 ease-in-out transform hover:scale-95 hover:bg-gray-100 dark:hover:bg-slate-500 p-2 rounded-lg"
                                > -->
                                <div
                                    class="flex items-center space-x-4 hover:bg-gray-100 dark:hover:bg-slate-600 p-2 rounded-lg"
                                >
                                    <!-- Avatar -->
                                    <img
                                        :src="following.avatar_url"
                                        alt="Avatar"
                                        class="w-10 h-10 rounded-full object-cover"
                                    />

                                    <!-- Text Content -->
                                    <div class="flex-1 overflow-hidden">
                                        <!-- Name -->
                                        <h3
                                            class="text-lg flex items-start justify-between truncate dark:text-white"
                                        >
                                            <!-- <span
                                            class="truncate"
                                            :class="{
                                                'font-bold':
                                                    !following.last_message_read_at &&
                                                    following.last_message_sender_id !==
                                                        authUser.id,
                                            }"
                                        >
                                            {{ following.name }}
                                                {{ following.surname }}
                                        </span> -->
                                            <span
                                                class="truncate"
                                                :class="{
                                                    'font-bold':
                                                        !following.last_message_read_at &&
                                                        following.last_message_sender_id !==
                                                            authUser.id,
                                                }"
                                            >
                                                {{ getFullName(following) }}
                                            </span>

                                            <span
                                                v-if="
                                                    following.unread_count > 0
                                                "
                                                class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-1 flex-shrink-0"
                                            >
                                                {{
                                                    following.unread_count > 99
                                                        ? "99+"
                                                        : following.unread_count
                                                }}
                                            </span>
                                        </h3>

                                        <!-- Message and Unread Count -->
                                        <p
                                            class="text-sm text-gray-500 dark:text-slate-500 flex justify-between items-center truncate lg:pe-20"
                                            :class="{
                                                'font-bold text-black':
                                                    !following.last_message_read_at &&
                                                    following.last_message_sender_id !==
                                                        authUser.id,
                                            }"
                                        >
                                            <span
                                                class="truncate dark:text-white"
                                            >
                                                <span class="font-medium">
                                                    {{
                                                        following.last_message_sender_id ===
                                                        authUser.id
                                                            ? "You"
                                                            : following.last_message_sender_name
                                                    }}:
                                                </span>
                                                {{
                                                    following.last_message
                                                        ? following.last_message
                                                        : "Sent an Attachment"
                                                }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </Link>
                        </li>
                    </ul>
                    <!-- If there are no users -->
                    <div
                        v-else
                        class="text-center text-gray-500 mt-8 dark:text-slate-400"
                    >
                        No conversations found.
                    </div>
                </TabPanel>

                <!-- Group Chats -->
                <TabPanel>
                    <div
                        class="sticky flex justify-between top-0 bg-white dark:bg-slate-900 shadow-sm py-2 px-4 items-center"
                    >
                        <h2 class="text-xl font-bold dark:text-white">
                            Group Chats
                        </h2>
                        <button
                            @click="showNewGroupModal = true"
                            class="text-center items-center bg-red-500 hover:bg-red-600 text-white rounded py-1 px-2"
                        >
                            <!-- new group -->
                            <PlusIcon class="h-7 w-7" />
                        </button>
                    </div>
                    <ul v-if="groupChats.length">
                        <li
                            v-for="group in groupChats"
                            :key="group.id"
                            class="mb-1 px-3 py-1 border-b border-gray-200"
                        >
                            <Link :href="`/group-chat/${group.id}`">
                                <!-- <div
                                    class="flex items-center space-x-4 transition-transform duration-300 ease-in-out transform hover:scale-105 hover:bg-gray-100 dark:hover:bg-slate-500 p-2 rounded-lg"
                                > -->
                                <div
                                    class="flex items-center space-x-4 hover:bg-gray-100 dark:hover:bg-slate-600 p-2 rounded-lg"
                                >
                                    <img
                                        :src="group.photo"
                                        alt="Avatar"
                                        class="w-10 h-10 rounded-full object-cover"
                                    />
                                    <div>
                                        <h3
                                            class="text-lg font-medium dark:text-white"
                                        >
                                            {{ group.name }}
                                        </h3>
                                    </div>
                                </div>
                            </Link>
                        </li>
                    </ul>
                    <!-- If there are no users -->
                    <div
                        v-else
                        class="text-center text-gray-500 mt-8 dark:text-slate-400"
                    >
                        No conversations found.
                    </div>
                </TabPanel>

                <!-- Kalakalkoto -->
                <TabPanel>
                    <div
                        class="sticky flex justify-between top-0 bg-white dark:bg-slate-900 shadow-sm py-3 px-4 items-center"
                    >
                        <h2 class="text-xl font-bold dark:text-white">
                            Kalakalkoto Conversations
                        </h2>
                    </div>
                    <ul v-if="kalakalUsers.length">
                        <li
                            v-for="kalakalUser in kalakalUsers"
                            :key="kalakalUser.id"
                            class="mb-1 px-3 py-1 border-b border-gray-200"
                        >
                            <Link
                                :href="
                                    route('chat.kalakalkoto', {
                                        user: kalakalUser.id,
                                    })
                                "
                            >
                                <!-- <div
                                    class="flex items-center space-x-4 transition-transform duration-300 ease-in-out transform hover:scale-105 hover:bg-gray-100 dark:hover:bg-slate-500 p-2 rounded-lg"
                                > -->
                                <div
                                    class="flex items-center space-x-4 hover:bg-gray-100 dark:hover:bg-slate-600 p-2 rounded-lg"
                                >
                                    <img
                                        :src="kalakalUser.avatar_url"
                                        alt="Avatar"
                                        class="w-10 h-10 rounded-full object-cover"
                                    />
                                    <!-- Text Content -->
                                    <div class="flex-1 overflow-hidden">
                                        <!-- Name -->
                                        <h3
                                            class="text-lg flex items-start justify-between truncate dark:text-white"
                                        >
                                            <span
                                                class="truncate"
                                                :class="{
                                                    'font-bold':
                                                        !kalakalUser.last_message_read_at &&
                                                        kalakalUser.last_message_sender_id !==
                                                            authUser.id,
                                                }"
                                            >
                                                {{ kalakalUser.name }}
                                                {{ kalakalUser.surname }}
                                            </span>

                                            <span
                                                v-if="
                                                    kalakalUser.unread_count > 0
                                                "
                                                class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-1 flex-shrink-0"
                                            >
                                                {{
                                                    kalakalUser.unread_count >
                                                    99
                                                        ? "99+"
                                                        : kalakalUser.unread_count
                                                }}
                                            </span>
                                        </h3>

                                        <!-- Message and Unread Count -->
                                        <p
                                            class="text-sm text-gray-500 dark:text-slate-500 flex justify-between items-center truncate lg:pe-20"
                                            :class="{
                                                'font-bold text-black':
                                                    !kalakalUser.last_message_read_at &&
                                                    kalakalUser.last_message_sender_id !==
                                                        authUser.id,
                                            }"
                                        >
                                            <span
                                                class="truncate dark:text-white"
                                            >
                                                <span class="font-medium">
                                                    {{
                                                        kalakalUser.last_message_sender_id ===
                                                        authUser.id
                                                            ? "You"
                                                            : kalakalUser.last_message_sender_name
                                                    }}:
                                                </span>
                                                {{
                                                    kalakalUser.last_message
                                                        ? kalakalUser.last_message
                                                        : "Sent an Attachment"
                                                }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </Link>
                        </li>
                    </ul>
                    <!-- If there are no users -->
                    <div
                        v-else
                        class="text-center text-gray-500 mt-8 dark:text-slate-400"
                    >
                        No conversations found.
                    </div>
                </TabPanel>

                <!-- Adkoto -->
                <TabPanel>
                    <div
                        class="sticky flex justify-between top-0 bg-white dark:bg-slate-900 shadow-sm py-3 px-4 items-center"
                    >
                        <h2 class="text-xl font-bold dark:text-white">
                            Adkoto Conversations
                        </h2>
                    </div>
                    <ul v-if="adkotoUsers.length">
                        <li
                            v-for="adkotoUser in adkotoUsers"
                            :key="adkotoUser.id"
                            class="mb-1 px-3 py-1 border-b border-gray-200"
                        >
                            <Link
                                :href="
                                    route('chat.adkoto', {
                                        user: adkotoUser.id,
                                    })
                                "
                            >
                                <!-- <div
                                    class="flex items-center space-x-4 transition-transform duration-300 ease-in-out transform hover:scale-105 hover:bg-gray-100 dark:hover:bg-slate-500 p-2 rounded-lg"
                                > -->
                                <div
                                    class="flex items-center space-x-4 hover:bg-gray-100 dark:hover:bg-slate-600 p-2 rounded-lg"
                                >
                                    <img
                                        :src="adkotoUser.avatar_url"
                                        alt="Avatar"
                                        class="w-10 h-10 rounded-full object-cover"
                                    />
                                    <!-- Text Content -->
                                    <div class="flex-1 overflow-hidden">
                                        <!-- Name -->
                                        <h3
                                            class="text-lg flex items-start justify-between truncate dark:text-white"
                                        >
                                            <span
                                                class="truncate"
                                                :class="{
                                                    'font-bold':
                                                        !adkotoUser.last_message_read_at &&
                                                        adkotoUser.last_message_sender_id !==
                                                            authUser.id,
                                                }"
                                            >
                                                {{ adkotoUser.name }}
                                                {{ adkotoUser.surname }}
                                            </span>

                                            <span
                                                v-if="
                                                    adkotoUser.unread_count > 0
                                                "
                                                class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-1 flex-shrink-0"
                                            >
                                                {{
                                                    adkotoUser.unread_count > 99
                                                        ? "99+"
                                                        : adkotoUser.unread_count
                                                }}
                                            </span>
                                        </h3>

                                        <!-- Message and Unread Count -->
                                        <p
                                            class="text-sm text-gray-500 dark:text-slate-500 flex justify-between items-center truncate lg:pe-20"
                                            :class="{
                                                'font-bold text-black':
                                                    !adkotoUser.last_message_read_at &&
                                                    adkotoUser.last_message_sender_id !==
                                                        authUser.id,
                                            }"
                                        >
                                            <span
                                                class="truncate dark:text-white"
                                            >
                                                <span class="font-medium">
                                                    {{
                                                        adkotoUser.last_message_sender_id ===
                                                        authUser.id
                                                            ? "You"
                                                            : adkotoUser.last_message_sender_name
                                                    }}:
                                                </span>
                                                {{
                                                    adkotoUser.last_message
                                                        ? adkotoUser.last_message
                                                        : "Sent an Attachment"
                                                }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </Link>
                        </li>
                    </ul>
                    <!-- If there are no users -->
                    <div
                        v-else
                        class="text-center text-gray-500 mt-8 dark:text-slate-400"
                    >
                        No conversations found.
                    </div>
                </TabPanel>
            </TabPanels>
        </TabGroup>
    </div>
    <CreateGroupModal v-model="showNewGroupModal" @create="onGroupCreate" />

    <SearchModal :isOpen="isSearchModalOpen" @close="closeSearchModal" />
</template>

<script setup>
import { defineProps, ref, onMounted, onUnmounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { PlusIcon, MagnifyingGlassIcon } from "@heroicons/vue/24/solid";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
import TabItem from "@/Components/Chat/TabItem.vue";

import CreateGroupModal from "@/Components/GroupChat/CreateGroupModal.vue";
import SearchModal from "@/Components/Chat/SearchModal.vue";

const authUser = usePage().props.auth.user;

const props = defineProps({
    followings: {
        type: Array,
        required: true,
    },
    // participants: {
    // 	type: Array,
    // 	required: true,
    // },
    groupChats: {
        type: Array,
        required: true,
    },
    kalakalUsers: {
        type: Array,
    },
    adkotoUsers: {
        type: Array,
    },
});

const showNewGroupModal = ref(false);

const isSearchModalOpen = ref(false);

const openSearchModal = () => {
    isSearchModalOpen.value = true;
};

const closeSearchModal = () => {
    isSearchModalOpen.value = false;
};

// function onGroupCreate(group) {
// 	props.groups.unshift(group);
// }

function getFullName(user) {
    return user.surname ? `${user.name} ${user.surname}` : user.name;
}
</script>
