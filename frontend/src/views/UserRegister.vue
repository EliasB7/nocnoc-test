<template>
  <div class="max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-4">Registrar Usuario</h2>
    <form @bmit.prevent="registerUser">
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
          class="w-full px-3 text-black py-2 border rounded"
          type="email"
          id="email"
          v-model="formData.email"
          required
        />
      </div>
      <div class="mb-4">
        <label class="block mb-1" for="password">Contraseña:</label>
        <input
          class="w-full text-black px-3 py-2 border rounded"
          type="password"
          id="password"
          v-model="formData.password"
          required
        />
      </div>
      <div>
        <button class="px-4 py-2 bg-blue-500 text-black rounded cursor-pointer" type="submit">
          Registrar usuario
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'UserRegister',
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
    async registerUser() {
      try {
        const response = await axios.post(
          'http://127.0.0.1:8000/api/auth/register',
          this.formData
        )(response.data)
        alert('Registro Exitoso')
      } catch (error) {
        console.error('Error al registrar usuario:', error.response.data)
      }
    }
  }
}
</script>
