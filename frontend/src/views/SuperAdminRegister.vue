<template>
  <div class="max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-4">Registrar Superadministrador</h2>
    <form @submit.prevent="registerSuperadmin">
      <div class="mb-4">
        <label class="block mb-1" for="name">Nombre:</label>
        <input
          class="w-full px-3 py-2 text-black border rounded"
          type="text"
          id="name"
          v-model="formData.name"
          required
        />
      </div>
      <div class="mb-4">
        <label class="block mb-1" for="email">Email:</label>
        <input
          class="w-full px-3 py-2 border text-black rounded"
          type="email"
          id="email"
          v-model="formData.email"
          required
        />
      </div>
      <div class="mb-4">
        <label class="block mb-1" for="password">Contraseña:</label>
        <input
          class="w-full px-3 py-2 border rounded text-black"
          type="password"
          id="password"
          v-model="formData.password"
          required
        />
      </div>
      <div>
        <button class="px-4 py-2 bg-blue-500 text-white rounded cursor-pointer" type="submit">
          Registrar Superadministrador
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'SuperAdminRegister',
  data() {
    return {
      formData: {
        name: '',
        email: '',
        password: ''
      }
    }
  },
  methods: {
    async registerSuperadmin() {
      try {
        const response = await axios.post(
          'http://127.0.0.1:8000/api/auth/register-admin',
          this.formData
        )
        alert('Registrado exitosamente :)')
        console.log(response.data)
      } catch (error) {
        console.error('Error al registrar superadministrador:', error.response.data)
      }
    }
  }
}
</script>
