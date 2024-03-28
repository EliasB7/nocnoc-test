import './assets/main.css'

import { createApp } from 'vue'
import { createStore } from 'vuex'
import App from './App.vue'
import router from './router'
import VueAxios from 'vue-axios'
import axios from 'axios'

const app = createApp(App)

const store = createStore({
  state: {
    resetToken: null,
    userType: null
  },
  mutations: {
    setResetToken(state, token) {
      state.resetToken = token
    },
    setUserType(state, userType) {
      state.userType = userType
    }
  },
  actions: {},
  getters: {}
})

app.use(VueAxios, axios)
app.use(store)
app.use(router)

app.mount('#app')
