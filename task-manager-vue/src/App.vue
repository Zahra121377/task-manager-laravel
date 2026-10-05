<script setup>
import { ref, onMounted } from 'vue'
import api from './services/api'

const email = ref('')
const password = ref('')
const tasks = ref([])
const title = ref('')
const description = ref('')
const priority = ref('')
onMounted(async () => {
  if (localStorage.getItem('token')) {
    await getTasks()
  }
})
const handleLogin = async () => {
  const response = await api.post('/login', {
    email: email.value,
    password: password.value,
  })

  const token = response.data.token

  localStorage.setItem('token', token)

  console.log('Token saved to localStorage')
  await getTasks()
}
const handleLogout = async () => {
  await api.post('/logout')
  localStorage.removeItem('token')
  tasks.value = []
  console.log('Token removed from localStorage')
}

const getTasks = async () => {
  const response = await api.get('/tasks')

  tasks.value = response.data.data
}
const createTask = async () => {
  const response = await api.post('/tasks', {
    title: title.value,
    description: description.value,
    priority: priority.value,
  })
  tasks.value.unshift(response.data)

  title.value = ''
  description.value = ''
  priority.value = 'medium'
}
const deleteTask = async (taskId) => {
  await api.delete(`/tasks/${taskId}`)
  tasks.value = tasks.value.filter((task) => task.id !== taskId)
}
const updateTask = async (taskId) => {
  const taskToUpdate = tasks.value.find((task) => task.id === taskId)
  if (!taskToUpdate) return

  const updatedTitle = prompt('Enter new title:', taskToUpdate.title)
  const updatedDescription = prompt('Enter new description:', taskToUpdate.description)
  const updatedPriority = prompt('Enter new priority (low, medium, high):', taskToUpdate.priority)

  if (updatedTitle && updatedDescription && updatedPriority) {
    const response = await api.put(`/tasks/${taskId}`, {
      title: updatedTitle,
      description: updatedDescription,
      priority: updatedPriority,
    })

    const index = tasks.value.findIndex((task) => task.id === taskId)
    tasks.value[index] = response.data
  }
}
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
      <button type="button" @click="getTasks">
        Get Tasks
      </button>
      <button type="button" @click="handleLogout">
        Logout
      </button> 
    </form>
    <h2>Create Task</h2>

    <form @submit.prevent="createTask">
      <input
        v-model="title"
        type="text"
        placeholder="Title"
      />

      <textarea
        v-model="description"
        placeholder="Description"
      ></textarea>

      <select v-model="priority">
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
      </select>

      <button type="submit">
        Create Task
      </button>
    </form>

    <div v-for="task in tasks" :key="task.id">
        <h3>{{ task.title }}</h3>
        <p>Description: {{ task.description }}</p>
        <p>Priority: {{ task.priority }}</p>
        <button @click="deleteTask(task.id)">Delete</button>
        <button @click="updateTask(task.id)">Update</button>
      </div>
  </div>
</template>