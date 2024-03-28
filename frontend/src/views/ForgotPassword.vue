<template>
  <div>
    <h2>Forgot Password</h2>
    <form @submit.prevent="submitForm">
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="email" required />
      </div>
      <button type="submit">Submit</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'ForgotPassword',
  data() {
    return {
      email: ''
    }
  },
  methods: {
    submitForm() {
      axios
        .post('http://127.0.0.1:8000/api/auth/forgot-password', { email: this.email })
        .then(() => {
          this.resetToken(this.token)

          alert('Link Enviado')
        })
        .catch((error) => {
          error
          // Manejar el error si es necesario
        })
    }
  }
}
</script>
