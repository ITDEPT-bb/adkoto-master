<template>
    <div
        ref="messageContainer"
        id="messages"
        class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thin scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch"
        @scroll="onScroll"
    >
        <div v-for="message in messages" :key="message.id" class="chat-message">
            <div
                class="flex items-center"
                :class="{
                    'justify-end': message.sender_id === authUser.id,
                    'justify-start': message.sender_id !== authUser.id,
                }"
            >
                <div
                    class="flex flex-col space-y-2 text-xs mx-2"
                    :class="{
                        'order-1 items-end': message.sender_id === authUser.id,
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
                        <!-- <span
                            v-if="message.message"
                            class="block break-words whitespace-pre-wrap"
                        >
                            {{ message.message }}
                        </span> -->
                        <span
                            v-if="message.message"
                            class="block break-words whitespace-pre-wrap overflow-hidden break-all"
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
                                    v-if="attachment.mime.startsWith('image/')"
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
                                        attachment.mime.startsWith('video/')
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
                    <small v-if="message.temp" class="text-gray-400 pl-1.5"
                        >Sending...</small
                    >
                    <small v-else class="text-gray-400 pl-1.5">{{
                        formatTime(message.created_at)
                    }}</small>
                </div>
                <img
                    v-if="message.sender_id !== authUser.id"
                    :src="user.avatar_url"
                    alt="Profile Picture"
                    class="w-6 h-6 rounded-full"
                />
            </div>
        </div>
        <!-- Image Modal -->
        <ImageModal
            :src="currentImageSrc"
            :isVisible="isImageModalVisible"
            @update:isVisible="isImageModalVisible = $event"
        />
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from "vue";
import { Link } from "@inertiajs/vue3";
import ImageModal from "@/Components/Chat/ImageModal.vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

const notificationSound = new Audio("/audio/notification_sound.wav");
notificationSound.loop = false;

dayjs.extend(relativeTime);

const props = defineProps({
    messages: Array,
    authUser: Object,
    user: Object,
    conversation: Object,
    showTime: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(["onScroll"]);

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

const isImageModalVisible = ref(false);
const currentImageSrc = ref("");

const openImageModal = (src) => {
    currentImageSrc.value = src;
    isImageModalVisible.value = true;
};

const messages = ref([...props.messages]);
const authUser = props.authUser;
const user = props.user;
const conversation = props.conversation;
const messageContainer = ref(null);
const isUserScrolling = ref(false);

const scrollToBottom = async () => {
    await nextTick();
    if (messageContainer.value) {
        messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
    }
};

const onScroll = () => {
    if (messageContainer.value) {
        const bottomThreshold = 50;
        const isAtBottom =
            messageContainer.value.scrollHeight -
                messageContainer.value.scrollTop -
                messageContainer.value.clientHeight <
            bottomThreshold;
        isUserScrolling.value = !isAtBottom;
    }
};

onMounted(() => {
    window.Echo.private(`chat.conversations.${conversation.id}`).listen(
        "MessageSent",
        (event) => {
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
            // notificationSound.play();
        }
    );
    scrollToBottom();
});

watch(
    () => props.messages,
    (newMessages) => {
        if (newMessages) {
            messages.value = newMessages;
            scrollToBottom();
        }
    }
);
</script>

<style scoped>
#messages {
    scroll-behavior: smooth;
}
</style>
