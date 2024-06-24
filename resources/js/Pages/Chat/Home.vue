<template>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 p-4 h-full">
      <!-- Following List Section -->
      <FollowingList :followings="followings" @selectUser="selectUser" />

      <!-- Chat Window Section -->
      <ChatWindow :selectedUser="selectedUser" :conversation="conversation" :newMessage="newMessage" @sendMessage="sendMessage" />

      <!-- User Info Section -->
      <UserInfo :selectedUser="selectedUser" />
    </div>
  </template>

  <script setup>
  import FollowingList from '@/Components/Chat/FollowingList.vue'
  import ChatWindow from '@/Components/Chat/ChatWindow.vue'
  import UserInfo from '@/Components/Chat/UserInfo.vue'
  import { ref, onMounted } from 'vue'
  import axiosClient from '@/axiosClient.js'

  const followings = ref([])
  const selectedUser = ref(null)
  const conversation = ref(null)
  const newMessage = ref("")

  const fetchFollowings = async () => {
    try {
      const response = await axiosClient.get('/followings')
      followings.value = response.data
    } catch (error) {
      console.error('Error fetching followings:', error)
    }
  }

  const selectUser = async (user) => {
    try {
      selectedUser.value = user
      const response = await axiosClient.get(`/chat/conversations/${user.id}`)
      conversation.value = response.data
    } catch (error) {
      console.error('Error fetching conversation:', error)
    }
  }

  onMounted(() => {
    fetchFollowings()
  })
  </script>
