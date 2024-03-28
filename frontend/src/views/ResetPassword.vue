<template>
  <div>
    <h2>Reset Password</h2>
    <form @submit.prevent="submitForm">
      <div>
        <label for="email">Email:</label>
        <input class="text-black" type="text" id="email" v-model="email" required />
      </div>
      <div>
        <label for="password">New Password:</label>
        <input class="text-black" type="password" id="password" v-model="password" required />
      </div>
      <div>
        <label for="confirmPassword">Confirm New Password:</label>
        <input
          type="password"
          class="text-black"
          id="confirmPassword"
          v-model="confirmPassword"
          required
        />
      </div>
      <button type="submit">Submit</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'ResetPassword',
  data() {
    return {
      email: '',
      password: '',
      confirmPassword: '',
      resetToken: null // Almacena el token de reseteo
    }
  },
  mounted() {
    this.resetToken = this.$route.params.token
  },
  methods: {
    submitForm() {
      axios
        .post(`http://127.0.0.1:8000/api/auth/reset-password/${this.resetToken}`, {
          password: this.password,
          password_confirmation: this.confirmPassword,
          token: this.resetToken,
          email: this.email
        })
        .then(() => {
          alert('LINK ENVIADO A SU CORREO ')
        })
        .catch((error) => {
          alert('Error en el cambio', error)
        })
    }
  }
}
</script>
