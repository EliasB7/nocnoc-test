<script>
import axios from 'axios'

export default {
  data() {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    login() {
      axios
        .post('http://127.0.0.1:8000/api/auth/login', {
          email: this.email,
          password: this.password
        })
        .then((response) => {
          const userType = response.data.user_type
          const token = response.data.access_token
          const userName = response.data.userName

          if (userType === 'superadmin') {
            localStorage.setItem('userType', 'superadmin', 'token')
            localStorage.setItem('token', token)
            this.$router.push({ name: 'admin-dashboard' })
          } else {
            localStorage.setItem('userType', 'normal')
            localStorage.setItem('token', token)
            localStorage.setItem('userName', userName)

            this.$router.push({ name: 'TaskList' })
          }
        })
        .catch((error) => {
          console.error('Error:', error)
          let errorMessage = 'An error occurred'
          if (error.response && error.response.data && error.response.data.error) {
            errorMessage = error.response.data.error
          } else if (error.message) {
            errorMessage = error.message
          }
          alert('Error: ' + errorMessage)
        })
    }
  }
}
</script>

<template>
  <main>
    <section class="bg-gray-50 dark:bg-gray-900">
      <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <div
          class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700"
        >
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1
              class="text-xl font-bold leading-tight tracking-tight text-black md:text-2xl dark:text-white"
            >
              Sign in to your account
            </h1>
            <form class="space-y-4 md:space-y-6" @submit.prevent="login">
              <div>
                <label for="email" class="block mb-2 text-sm font-medium text-black dark:text-white"
                  >Your email</label
                >
                <input
                  type="email"
                  name="email"
                  id="email"
                  class="bg-gray-50 border border-gray-300 text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="name@company.com"
                  required=""
                  v-model="email"
                />
              </div>
              <div>
                <label
                  for="password"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                  >Password</label
                >
                <input
                  type="password"
                  name="password"
                  id="password"
                  placeholder="••••••••"
                  class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  v-model="password"
                />
              </div>
              <div class="flex items-center justify-between">
                <router-link to="/forgot-password">Forgot Password?</router-link>
              </div>
              <button
                type="submit"
                class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
              >
                Sign in
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>
