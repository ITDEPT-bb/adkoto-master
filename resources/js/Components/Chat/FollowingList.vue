<template>
    <div
        class="h-full overflow-hidden w-full p-4 bg-white dark:bg-slate-900 rounded-lg shadow"
    >
        <div class="flex h-full gap-4 lg:gap-0 flex-col sm:flex-row">
            <!-- Followings Column -->
            <div class="flex-1 overflow-y-auto scrollbar-thin pr-4">
                <div
                    class="sticky flex justify-between top-0 bg-white dark:bg-slate-900 shadow-sm p-2"
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
                <ul>
                    <li
                        v-for="following in followings"
                        :key="following.id"
                        class="mb-1 px-3 py-1 border-b border-gray-200"
                    >
                        <Link
                            :href="`/chat/conversation/adktu/${following.id}`"
                        >
                            <div
                                class="flex items-center space-x-4 transition-transform duration-300 ease-in-out transform hover:scale-105 hover:bg-slate-500 p-2 rounded-lg"
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
                                    <!-- <h3
										class="text-lg truncate"
										:class="{ 'font-bold': !following.last_message_read_at }">
										{{ following.name }} {{ following.surname }}
									</h3> -->
                                    <h3
                                        class="text-lg flex items-start justify-between truncate dark:text-white"
                                    >
                                        <span
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
                                        </span>

                                        <span
                                            v-if="following.unread_count > 0"
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
                                    <!-- <p
										class="text-sm text-gray-500 flex justify-between items-center truncate lg:pe-20"
										:class="{ 'font-bold text-black': !following.last_message_read_at }">
										<span class="truncate">
											<span class="font-medium">{{ following.last_message_sender_name }}:</span>
											{{ following.last_message ? following.last_message : "No messages yet" }}
										</span>
										<span
											v-if="following.unread_count > 0"
											class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-1 flex-shrink-0">
											{{ following.unread_count }}
										</span>
									</p> -->
                                    <p
                                        class="text-sm text-gray-500 dark:text-slate-500 flex justify-between items-center truncate lg:pe-20"
                                        :class="{
                                            'font-bold text-black':
                                                !following.last_message_read_at &&
                                                following.last_message_sender_id !==
                                                    authUser.id,
                                        }"
                                    >
                                        <span class="truncate dark:text-white">
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
                                                    : "No messages yet"
                                            }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </Link>
                    </li>
                </ul>
            </div>

            <!-- Group Column -->
            <div class="flex-1 overflow-y-auto scrollbar-thin pr-4">
                <div
                    class="sticky flex justify-between top-0 bg-white dark:bg-slate-900 shadow-sm p-2"
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
                <ul>
                    <li
                        v-for="group in groupChats"
                        :key="group.id"
                        class="mb-1 px-3 py-1 border-b border-gray-200"
                    >
                        <Link :href="`/group-chat/${group.id}`">
                            <div
                                class="flex items-center space-x-4 transition-transform duration-300 ease-in-out transform hover:scale-105 hover:bg-gray-100 dark:hover:bg-slate-500 p-2 rounded-lg"
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
            </div>

            <!-- Others Column -->
            <!-- <div class="flex-1 overflow-y-auto pl-4">
				<div class="sticky top-0 bg-white shadow-sm z-10 p-2">
					<h2 class="text-xl font-bold">Others</h2>
				</div>
				<ul>
					<li
						v-for="participant in participants"
						:key="participant.id"
						class="mb-1 px-3 py-1 border-b border-gray-200">
						<Link :href="`/chat/conversation/adktu/${participant.id}`">
							<div
								class="flex items-center space-x-4 transition-transform duration-300 ease-in-out transform hover:scale-105 hover:bg-gray-100 p-2 rounded-lg">
								<img
									:src="participant.avatar_url"
									alt="Avatar"
									class="w-10 h-10 rounded-full object-cover" />
								<div>
									<h3 class="text-lg font-medium">
										{{ participant.name }}
										{{ participant.surname }}
									</h3>
									<p class="text-sm text-gray-600">@{{ participant.username }}</p>
								</div>
							</div>
						</Link>
					</li>
				</ul>
			</div> -->
        </div>
    </div>
    <CreateGroupModal v-model="showNewGroupModal" @create="onGroupCreate" />

    <SearchModal :isOpen="isSearchModalOpen" @close="closeSearchModal" />
</template>

<script setup>
import { defineProps, ref, onMounted, onUnmounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { PlusIcon, MagnifyingGlassIcon } from "@heroicons/vue/24/solid";

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
</script>
