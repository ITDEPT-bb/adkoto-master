<template>
    <Head title="Chat" />

    <AuthenticatedLayout>
        <div class="h-full max-w-lg mx-auto overflow-y-auto bg-white p-4 rounded-lg shadow-md">
            <h1 class="text-xl font-bold mb-4">Chat with {{ user.name }}</h1>
            <div
                v-for="message in messages"
                :key="message.id"
                :class="{
                    'bg-blue-100': message.sender_id !== user.id,
                    'bg-gray-100': message.sender_id === user.id,
                }"
                class="p-3 rounded-lg mb-2"
            >
                <strong
                    >{{
                        message.sender_id !== user.id ? "You" : user.name
                    }}:</strong
                >
                {{ message.body }}
            </div>
            <div class="mt-4">
                <input
                    v-model="newMessage"
                    @keyup.enter="sendMessage"
                    type="text"
                    class="w-full p-2 border rounded-lg"
                    placeholder="Type a message..."
                />
            </div>
        </div>
    </AuthenticatedLayout>
    <UpdateProfileReminder />
</template>

<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";

const { props } = usePage();
const user = ref(props.user);
const messages = ref(props.messages);
const newMessage = ref("");

// echo.private(`chat.${user.value.id}`)
//     .listen('MessageSent', (e) => {
//         messages.value.push(e.message);
//     });

// const sendMessage = async () => {
//     await axios.post(`/chat/${user.value.id}`, { body: newMessage.value });
//     newMessage.value = '';
// };
</script>
