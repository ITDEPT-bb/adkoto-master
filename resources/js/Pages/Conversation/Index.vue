<template>
    <div
        class="flex flex-col h-screen max-w-2xl mx-auto border border-gray-300 rounded-lg overflow-hidden"
    >
        <div
            class="flex items-center space-x-4 sm:mb-0 p-6"
            style="background-color: #0076be"
        >
            <Link :href="route('chat.index')" aria-label="Back to chat index">
                <button
                    class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300"
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
            <div class="relative">
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
            <div
                class="flex flex-col sm:flex-row sm:items-center leading-tight"
            >
                <div class="flex items-center gap-10">
                    <div
                        class="sm:text-lg text-md font-semibold text-center sm:text-left text-white"
                    >
                        {{ user.name }} {{ user.surname }}
                        <span
                            class="text-sm font-light text-white sm:block hidden"
                            >@{{ user.username }}</span
                        >
                    </div>
                    <!-- Hamburger Button beside Name on Mobile -->
                    <button
                        @click="toggleMenu"
                        class="sm:hidden inline-flex items-center justify-center rounded-lg ml-2 h-10 w-10 transition duration-500 ease-in-out text-white hover:bg-gray-300 hover:text-black focus:outline-none"
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
        </div>

        <template v-if="!isBlockedByAuthUser && !isBlockedByOtherUser">
            <div
                ref="messageContainer"
                class="flex-1 p-4 overflow-y-auto space-y-4"
            >
                <div
                    v-for="message in messages"
                    :key="message.id"
                    class="chat-message"
                >
                    <div
                        class="flex items-start"
                        :class="{
                            'justify-end': message.sender_id === authUser.id,
                            'justify-start': message.sender_id !== authUser.id,
                        }"
                    >
                        <div
                            class="flex flex-col space-y-2 text-xs mx-2"
                            :class="{
                                'order-1 items-end':
                                    message.sender_id === authUser.id,
                                'order-2 items-start':
                                    message.sender_id !== authUser.id,
                            }"
                        >
                            <div
                                class="max-w-lg p-2 rounded-lg"
                                :class="{
                                    'bg-blue-600 text-white':
                                        message.sender_id === authUser.id,
                                    'bg-gray-300 text-gray-600':
                                        message.sender_id !== authUser.id,
                                }"
                            >
                                <span
                                    v-if="message.message"
                                    class="block break-words whitespace-pre-wrap"
                                >
                                    {{ message.message }}
                                </span>

                                <!-- Display attachments if available -->
                                <div
                                    v-if="
                                        message.attachments &&
                                        message.attachments.length > 0
                                    "
                                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mt-2"
                                >
                                    <div
                                        v-for="attachment in message.attachments"
                                        :key="attachment.id"
                                        class="rounded-lg inline-block m-1 break-words max-w-lg"
                                    >
                                        <!-- Render images -->
                                        <div
                                            v-if="
                                                attachment.mime.startsWith(
                                                    'image/'
                                                )
                                            "
                                        >
                                            <img
                                                :src="`/storage/${attachment.path}`"
                                                alt="Image"
                                                class="rounded-lg w-[100px] h-[100px] object-cover cursor-pointer"
                                                @click="
                                                    openImageModal(
                                                        `/storage/${attachment.path}`
                                                    )
                                                "
                                            />
                                        </div>

                                        <!-- Render videos -->
                                        <div
                                            v-else-if="
                                                attachment.mime.startsWith(
                                                    'video/'
                                                )
                                            "
                                        >
                                            <video
                                                :src="`/storage/${attachment.path}`"
                                                controls
                                                class="rounded-lg w-[100px] h-[100px] object-cover"
                                            ></video>
                                        </div>

                                        <!-- Render other file types -->
                                        <div v-else>
                                            <Link
                                                :href="`/storage/${attachment.path}`"
                                                target="_blank"
                                                class="text-blue-500 underline"
                                            >
                                                {{ attachment.name }}
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <small
                                v-if="message.temp"
                                class="text-gray-400 pl-1.5"
                                >Sending...</small
                            >
                            <small v-else class="text-gray-400 pl-1.5">{{
                                formatTime(message.created_at)
                            }}</small>
                            <!-- Show status -->
                        </div>
                        <img
                            v-if="message.sender_id !== authUser.id"
                            :src="user.avatar_url"
                            alt="Profile Picture"
                            class="w-6 h-6 rounded-full"
                        />
                    </div>
                </div>
            </div>
        </template>

        <div class="flex p-4 border-t border-gray-300">
            <input
                v-model="newMessage"
                @keyup.enter="sendMessage"
                placeholder="Type a message..."
                class="flex-1 p-2 border border-gray-300 rounded mr-2"
            />
            <button
                @click="sendMessage"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700"
            >
                Send
            </button>
        </div>
    </div>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, nextTick } from "vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import axios from "axios";

dayjs.extend(relativeTime);

const props = defineProps({
    user: Object,
    messages: Array,
    conversation: Object,
    isBlockedByAuthUser: Boolean,
    isBlockedByOtherUser: Boolean,
    showTime: {
        type: Boolean,
        default: true,
    },
});

function formatTime(postDate) {
    const now = dayjs();
    const postTime = dayjs(postDate);

    if (now.diff(postTime, "day") < 1) {
        return postTime.fromNow();
    } else if (now.diff(postTime, "day") === 1) {
        return `Yesterday at ${postTime.format("h:mm A")}`;
    } else if (now.diff(postTime, "day") < 7) {
        return `${postTime.format("dddd [at] h:mm A")}`;
    } else {
        return postTime.format("MMMM D, YYYY [at] h:mm A");
    }
}

const user = props.user;
const messages = ref(props.messages);
const conversation = props.conversation;
const authUser = usePage().props.auth.user;

const newMessage = ref("");
const messageContainer = ref(null);

const scrollToBottom = () => {
    nextTick(() => {
        if (messageContainer.value) {
            messageContainer.value.scrollTop =
                messageContainer.value.scrollHeight;
        }
    });
};

onMounted(() => {
    scrollToBottom();

    window.Echo.private(`chat.conversations.${conversation.id}`).listen(
        "MessageSent",
        (event) => {
            // Remove the temporary message if it exists (match by sender and message content)
            const index = messages.value.findIndex(
                (msg) =>
                    msg.temp &&
                    msg.message === event.message.message &&
                    msg.sender_id === event.message.sender_id
            );
            if (index !== -1) {
                messages.value.splice(index, 1);
            }
            messages.value.push(event.message);
            scrollToBottom();
        }
    );
});

const sendMessage = async () => {
    if (newMessage.value.trim() !== "") {
        const tempMessage = {
            id: Date.now(), // temporary ID
            sender_id: authUser.id,
            sender: { id: authUser.id, name: "You" },
            message: newMessage.value,
            created_at: new Date().toISOString(),
            attachments: [],
            temp: true, // Mark this message as temporary
        };
        messages.value.push(tempMessage);

        const messageContent = newMessage.value;
        newMessage.value = "";
        scrollToBottom();

        try {
            await axios.post(route("chat.message.store", conversation.id), {
                message: messageContent,
            });
        } catch (error) {
            console.error("Failed to send message", error);
            // Optionally remove the optimistic message or alert the user
        }
    }
};
</script>
