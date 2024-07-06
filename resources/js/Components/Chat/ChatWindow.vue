<template>
    <div class="col-span-1 lg:col-span-6 h-full overflow-hidden bg-white rounded-lg shadow-md">
      <div class="flex flex-col h-full">
        <ChatHeader v-if="selectedUser" :user="selectedUser" />
        <div v-if="conversation" class="flex-1 overflow-y-auto space-y-4 px-4">
          <MessageList :messages="conversation.messages" :selectedUserId="selectedUser.id" />
          <ChatInput v-model="newMessage" @send="sendMessage" />
        </div>
        <div v-else class="flex-1 flex items-center justify-center text-gray-500">
          Select a user to start a conversation.
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import ChatHeader from '@/Components/Chat/ChatHeader.vue'
  import MessageList from '@/Components/Chat/MessageList.vue'
  import ChatInput from '@/Components/Chat/ChatInput.vue'
  import { useContext } from 'vue'

  const { selectedUser, conversation, newMessage, sendMessage } = useContext()
  </script>
