<script setup>
import { ref } from 'vue'
import api from './services/api'

const email = ref('')
const password = ref('')
const tasks = ref([])

const handleLogin = async () => {
  const response = await api.post('/login', {
    email: email.value,
    password: password.value,
  })

  const token = response.data.token

  localStorage.setItem('token', token)

  console.log('Token saved to localStorage')
}

const getTasks = async () => {
  const response = await api.get('/tasks')

  tasks.value = response.data.data
</script>

<template>
  <div>
    <h1>Task Manager</h1>

    <h2>Login</h2>

    <form @submit.prevent="handleLogin">
      <input
        v-model="email"
        type="email"
        placeholder="Email"
      />

      <input
        v-model="password"
        type="password"
        placeholder="Password"
      />

      <button type="submit">
        Login
      </button>
      <button @click="getTasks">
        Get Tasks
      </button>
    </form>
  </div>
</template>