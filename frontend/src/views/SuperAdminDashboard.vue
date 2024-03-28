<template>
  <div class="flex justify-center items-center h-screen">
    <div class="grid grid-cols-2 gap-4">
      <!-- Botones de funcionalidades -->
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        @click="registerUsers"
      >
        Registrar Usuarios
      </button>
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        @click="registerAdmin"
      >
        Registrar Administradores
      </button>
      <button
        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
        @click="viewTasks"
      >
        Ver las tareas
      </button>
      <button
        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
        @click="createTasks"
      >
        Crear tareas
      </button>
      <!-- Botón para descargar informe -->
      <button
        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
        @click="downloadReport"
      >
        Descargar Informe
      </button>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'SuperAdminDashboard',
  methods: {
    registerUsers() {
      this.$router.push({
        name: 'UserRegister'
      })
    },
    registerAdmin() {
      this.$router.push({
        name: 'SuperAdminRegister'
      })
    },
    viewTasks() {
      this.$router.push({
        name: 'TaskList'
      })
    },
    createTasks() {
      this.$router.push({
        name: 'CreateTask'
      })
    },
    downloadReport() {
      axios
        .get('http://127.0.0.1:8000/api/auth/generate-report', {
          responseType: 'blob'
        })
        .then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]))
          const link = document.createElement('a')
          link.href = url
          link.setAttribute('download', 'task_summary.pdf')
          document.body.appendChild(link)
          link.click()
        })
        .catch((error) => {
          console.error('Error generating task summary:', error)
        })
    }
  }
}
</script>
