<template>
  <form v-if="fields.length > 0" @submit.prevent="submitForm">
    <div class="mb-4">
      <label for="title" class="block text-sm font-medium">Título</label>
      <input
        v-model="editedTask.title"
        id="title"
        type="text"
        class="mt-1 p-2 border border-gray-300 text-black rounded-md w-full"
      />
    </div>
    <div class="mb-4">
      <label for="description" class="block text-sm font-medium">Descripción</label>
      <textarea
        v-model="editedTask.description"
        id="description"
        class="mt-1 p-2 border border-gray-300 text-black rounded-md w-full"
      ></textarea>
    </div>
    <div class="mb-4">
      <label for="status" class="block text-sm font-medium">{{ fields[0].label }}</label>
      <select
        v-model="editedTask.status"
        id="status"
        class="mt-1 p-2 border border-gray-300 text-black rounded-md w-full"
      >
        <option v-for="option in fields[0].options" :value="option.value" :key="option.value">
          {{ option.label }}
        </option>
      </select>
    </div>
    <button
      type="submit"
      class="bg-blue-500 hover:bg-blue-600 text-black font-bold py-2 px-4 rounded"
    >
      Guardar Cambios
    </button>
  </form>
</template>

<script>
import axios from 'axios'

export default {
  name: 'EditTask',
  data() {
    return {
      editedTask: {
        title: '',
        description: '',
        status: ''
      },
      fields: [],
      users: []
    }
  },
  methods: {
    loadTaskDetails(taskId) {
      axios
        .get(`http://127.0.0.1:8000/tasks/${taskId}`)
        .then((response) => {
          console.log('Response from server:', response.data.task)
          this.editedTask = response.data.task

          this.users = response.data.users

          this.fields = [
            {
              key: 'status',
              label: 'Estado',
              type: 'select',
              options: [
                {
                  value: 'todo',
                  label: 'Por hacer'
                },
                {
                  value: 'in_progress',
                  label: 'En progreso'
                },
                {
                  value: 'completed',
                  label: 'Completada'
                }
              ]
            }
          ]
        })
        .catch((error) => {
          console.error('Error fetching task details:', error)
        })
    },

    submitForm() {
      const token = localStorage.getItem('token')

      axios
        .put(
          `http://127.0.0.1:8000/api/auth/tasks/${this.$route.params.taskId}`,
          {
            ...this.editedTask,
            status: this.editedTask.status
          },
          {
            headers: {
              Authorization: `Bearer ${token}`
            }
          }
        )
        .then(() => {
          ;('Task updated successfully')
          this.$router.push({
            name: 'TaskList'
          })
        })
        .catch((error) => {
          console.error('Error updating task:', error)
        })
    }
  },

  mounted() {
    this.loadTaskDetails(this.$route.params.taskId)
  }
}
</script>
