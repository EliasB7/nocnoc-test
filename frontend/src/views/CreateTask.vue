<template>
  <div class="max-w-md mx-auto mt-8">
    <h2 class="text-xl font-semibold mb-4">Crear Nueva Tarea</h2>
    <form @submit.prevent="createTask" class="space-y-4 text-black">
      <div>
        <label for="title" class="block font-medium">Título:</label>
        <input
          type="text"
          id="title"
          v-model="newTask.title"
          required
          class="w-full border-gray-300 rounded-md focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        />
      </div>
      <div>
        <label for="description" class="block font-medium">Descripción:</label>
        <textarea
          id="description"
          v-model="newTask.description"
          required
          class="w-full border-gray-300 rounded-md focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        ></textarea>
      </div>
      <div>
        <label for="assigned_to" class="block font-medium">Asignar a:</label>
        <select
          id="assigned_to"
          v-model="newTask.assigned_to"
          required
          class="w-full border-gray-300 text-black rounded-md focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        >
          <option value="">Seleccionar usuario</option>
          <option v-for="usuario in users" :key="usuario.id" :value="usuario.id">
            {{ usuario.name }}
          </option>
        </select>
      </div>
      <button
        type="submit"
        class="w-full bg-indigo-500 text-black py-2 rounded-md hover:bg-indigo-600"
      >
        Crear Tarea
      </button>
    </form>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'CreateTask',
  data() {
    return {
      newTask: {
        title: '',
        description: '',
        assigned_to: ''
      },
      users: []
    }
  },
  mounted() {
    this.loadUsers()
  },
  methods: {
    loadUsers() {
      axios
        .get('http://127.0.0.1:8000/users')
        .then((response) => {
          response
          this.users = response.data.users
        })
        .catch((error) => {
          console.error('Error al cargar usuarios:', error)
        })
    },
    createTask() {
      axios
        .post('http://127.0.0.1:8000/api/auth/createTask', this.newTask)
        .then((response) => {
          'Tarea creada:', response.data
        })
        .catch((error) => {
          console.error('Error al crear tarea:', error)
        })
    }
  }
}
</script>
