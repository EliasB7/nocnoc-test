<template>
  <div class="max-w-2xl mx-auto mt-8 grid grid-cols-2 gap-8">
    <div>
      <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6">
          <h2 class="text-lg leading-6 font-medium text-gray-900">Detalles de la Tarea</h2>
        </div>
        <div class="border-t border-gray-200">
          <template v-if="loading">
            <div class="px-4 py-5 sm:px-6 flex justify-center">
              <svg
                class="animate-spin h-5 w-5 mr-3 text-blue-500"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
              >
                <circle
                  class="opacity-25"
                  cx="12"
                  cy="12"
                  r="10"
                  stroke="currentColor"
                  stroke-width="4"
                ></circle>
                <path
                  class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8V2.5A1.5 1.5 0 0010.5 1h-3A1.5 1.5 0 006 2.5V4a8 8 0 018 8z"
                ></path>
              </svg>
              <span class="text-lg text-gray-700">Cargando...</span>
            </div>
          </template>
          <template v-else-if="error">
            <div class="px-4 py-5 sm:px-6">
              <div class="bg-red-100 rounded-md p-4">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg
                      class="h-5 w-5 text-red-400"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                      aria-hidden="true"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M10 3a1 1 0 00-1 1v6a1 1 0 102 0V4a1 1 0 00-1-1zM9 14a1 1 0 012 0v1a1 1 0 11-2 0v-1zm11-6a9 9 0 11-18 0 9 9 0 0118 0zM2 10a8.99 8.99 0 014.08-7.52l1.43 1.43A7 7 0 1013 17.17V15a1 1 0 00-2 0v2.17a9 9 0 01-9-9z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Error</h3>
                    <div class="mt-2 text-sm text-red-700">
                      <p>{{ error }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </template>
          <template v-else>
            <dl>
              <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Título</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ task.title }}</dd>
              </div>
              <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ task.description }}</dd>
              </div>
              <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ task.status }}</dd>
              </div>
              <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Asignado a</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">
                  {{ task.assigned_to.name }}
                </dd>
              </div>
            </dl>
          </template>
        </div>
      </div>
      <div class="flex justify-center mt-4 space-x-4">
        <button
          @click="editTask(task.id)"
          class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
        >
          Editar
        </button>

        <button
          @click="deleteTask(task.id)"
          class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded"
        >
          Eliminar
        </button>
      </div>

      <div class="mt-4">
        <input type="file" @change="handleFileUpload" />
      </div>

      <div>
        <h2 class="text-lg leading-6 font-medium text-white">Archivos Adjuntos:</h2>
        <ul v-if="task && task.attachments">
          <li v-for="attachment in task.attachments" :key="attachment.id">
            <a :href="attachment.file_path" :download="attachment.file_name" target="_blank">{{
              attachment.file_name
            }}</a>
            <button @click="deleteAttachment(attachment.id)" class="ml-2 text-red-500">
              Eliminar
            </button>
          </li>
        </ul>
      </div>
    </div>

    <div class="bg-white flex flex-col items-center justify-center flex-no-wrap">
      <button
        @click="fetchComments"
        class="bg-blue-500 text-black hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
      >
        Cargar Comentarios
      </button>

      <div v-if="comments.length > 0" class="mt-4">
        <h3 class="text-lg font-medium text-black">Comentarios:</h3>
        <ul>
          <li v-for="comment in comments" :key="comment.id" class="text-black mt-2">
            {{ comment.comment }}
            <button @click="deleteComment(comment.id)" class="ml-2 text-red-500">Eliminar</button>
          </li>
        </ul>
      </div>
      <div>
        <p class="text-black mt-10">escribe tu comentario</p>
        <textarea
          v-model="newComment"
          class="mt-4 p-2 block w-full text-black border text-black border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
        ></textarea>

        <button
          @click="addComment"
          class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mt-4"
        >
          Agregar Comentario
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'TaskDetails',
  data() {
    return {
      task: null,
      loading: true,
      error: null,
      newComment: '',
      comments: []
    }
  },
  mounted() {
    this.fetchTaskDetails()
  },
  methods: {
    fetchComments() {
      const taskId = this.task.id
      axios
        .get(`http://127.0.0.1:8000/api/auth/tasks/${taskId}/comments`)
        .then((response) => {
          this.comments = response.data.comments
        })
        .catch((error) => {
          console.error('Error fetching comments:', error)
        })
    },

    addComment() {
      const token = localStorage.getItem('token')
      axios
        .post(
          `http://127.0.0.1:8000/api/auth/tasks/${this.task.id}/comments`,
          {
            comment: this.newComment
          },
          {
            headers: {
              Authorization: `Bearer ${token}`
            }
          }
        )
        .then((response) => {
          this.comments.push(response.data.comment)
          this.newComment = ''
        })
        .catch((error) => {
          console.error('Error adding comment:', error)
        })
    },

    deleteComment(commentId) {
      const token = localStorage.getItem('token')
      axios
        .delete(`http://127.0.0.1:8000/api/auth/tasks/comments/${commentId}`, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        })
        .then(() => {
          this.comments = this.comments.filter((comment) => comment.id !== commentId)
        })
        .catch((error) => {
          console.error('Error deleting comment:', error)
        })
    },
    fetchTaskDetails() {
      const taskId = this.$route.params.taskId
      axios
        .get(`http://127.0.0.1:8000/tasks/${taskId}`)
        .then((response) => {
          this.task = response.data.task
          this.getAllFiles(taskId)
          this.loading = false
        })
        .catch((error) => {
          console.error('Error fetching task details:', error)
          this.error = 'Error al cargar los detalles de la tarea'
          this.loading = false
        })
    },

    editTask(taskId) {
      const token = localStorage.getItem('token')
      const userType = localStorage.getItem('userType')
      const loggedInUserName = localStorage.getItem('userName')

      // Verificar si el usuario está autenticado
      if (token) {
        // Verificar si el usuario es un superadmin o un usuario normal
        if (userType === 'normal' || userType === 'superadmin') {
          // Verificar si el nombre de usuario coincide con el nombre de la persona asignada a la tarea
          if (
            this.task &&
            this.task.assigned_to &&
            this.task.assigned_to.name === loggedInUserName
          ) {
            this.$router.push({
              name: 'EditTask',
              params: {
                taskId: taskId
              }
            })
          } else if (userType === 'superadmin') {
            // Permitir acceso a los superadmins
            this.$router.push({
              name: 'EditTask',
              params: {
                taskId: taskId
              }
            })
          } else {
            console.error('No tienes permiso para editar esta tarea.')
          }
        } else {
          console.error('No tienes permiso para editar esta tarea.')
        }
      } else {
        console.error('Debes iniciar sesión para editar esta tarea.')
      }
    },
    deleteTask(taskId) {
      const token = localStorage.getItem('token')
      axios
        .delete(`http://127.0.0.1:8000/api/auth/tasks/${taskId}`, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        })
        .then(() => {
          alert('Godgamer')
        })
        .catch((error) => {
          console.error('Error deleting task:', error)
          let errorMessage = 'Ocurrió un error al eliminar la tarea'
          if (error.response && error.response.data && error.response.data.error) {
            errorMessage = error.response.data.error
          } else if (error.message) {
            errorMessage = error.message
          }
          alert('Error: ' + errorMessage)
        })
    },
    downloadTask(taskId) {
      const token = localStorage.getItem('token')
      axios
        .get(`http://127.0.0.1:8000/auth/tasks/${taskId}/attachments`, {
          headers: {
            Authorization: `Bearer ${token}`
          },
          responseType: 'blob'
        })
        .then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]))
          const link = document.createElement('a')
          link.href = url
          link.setAttribute('download', `task_attachment_${taskId}.pdf`)
          document.body.appendChild(link)
          link.click()
        })
        .catch((error) => {
          console.error('Error downloading task attachment:', error)
          let errorMessage = 'Ocurrió un error al descargar el archivo adjunto de la tarea'
          if (error.response && error.response.data && error.response.data.error) {
            errorMessage = error.response.data.error
          } else if (error.message) {
            errorMessage = error.message
          }
          alert('Error: ' + errorMessage)
        })
    },
    handleFileUpload(event) {
      const token = localStorage.getItem('token')

      const file = event.target.files[0]
      const formData = new FormData()
      formData.append('file', file)

      axios
        .post(`http://127.0.0.1:8000/api/auth/tasks/${this.task.id}/attachments`, formData, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        })
        .then((response) => {
          this.task.attachment = response.data.attachment
        })
        .catch((error) => {
          console.error('Error uploading attachment:', error)
        })
    },

    deleteAttachment(attachmentId) {
      const token = localStorage.getItem('token')

      axios
        .delete(`http://127.0.0.1:8000/api/auth/attachments/${attachmentId}`, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        })
        .then(() => {
          this.task.attachments = this.task.attachments.filter(
            (attachment) => attachment.id !== attachmentId
          )
        })
        .catch((error) => {
          console.error('Error deleting attachment:', error)
        })
    },
    getAllFiles(taskId) {
      axios
        .get(`http://127.0.0.1:8000/api/auth/tasks/${taskId}/attachments`)
        .then((response) => {
          this.task.attachments = response.data.attachments
        })
        .catch((error) => {
          console.error('Error fetching attachments:', error)
        })
    }
  }
}
</script>
