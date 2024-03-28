<template>
  <div class="max-w-10xl mx-auto mt-8">
    <h2 class="text-2xl font-semibold mb-4">Lista de Tareas</h2>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <div class="overflow-x-auto">
        <table class="font-weight min-w-full divide-y divide-gray-200 text-black">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider">
                Título
              </th>
              <th class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider">
                Descripción
              </th>
              <th class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider">
                Asignado a
              </th>
              <th class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider">
                Estado
              </th>
              <th class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider">
                Acciones
              </th>
            </tr>
          </thead>

          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="task in tasks" :key="task.id">
              <td class="px-2 py-4 whitespace-nowrap w-1/4">
                {{ task.title }}
              </td>
              <td class="px-2 py-4 whitespace-nowrap w-1/4">{{ task.description }}</td>
              <td class="px-2 py-4 whitespace-nowrap w-1/4">{{ task.assigned_to.name }}</td>
              <td class="px-2 py-4 whitespace-nowrap w-1/4">
                <span
                  :class="{
                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800':
                      task.status === 'Completed',
                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800':
                      task.status === 'In Progress',
                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800':
                      task.status === 'Pending'
                  }"
                >
                  {{ task.status }}
                </span>
              </td>
              <td class="px-2 py-4 whitespace-nowrap w-1/4">
                <router-link :to="{ name: 'TaskDetails', params: { taskId: task.id } }">
                  <button
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                  >
                    Detalles
                  </button>
                </router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { mapState } from 'vuex'

export default {
  name: 'TaskList',
  computed: {
    ...mapState({
      isSuperAdmin: (state) => state.userType === 'superadmin'
    })
  },
  data() {
    return {
      tasks: []
    }
  },
  mounted() {
    this.fetchTasks()
  },
  methods: {
    fetchTasks() {
      axios
        .get('http://127.0.0.1:8000/tasks/all')
        .then((response) => {
          this.tasks = response.data.tasks
        })
        .catch((error) => {
          console.error('Error fetching tasks:', error)
        })
    },
    goToTaskDetails(taskId) {
      ;`Navigating to task details for task ID ${taskId}`
    }
  }
}
</script>
