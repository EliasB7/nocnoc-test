<script setup>
import { ref } from 'vue'
import { RouterView } from 'vue-router'

const userRole = localStorage.getItem('userType')

const isSuperAdmin = ref(userRole === 'superadmin')
</script>

<template>
  <div>
    <nav v-if="isSuperAdmin" class="flex items-center justify-between px-4 py-2 bg-yellow-500">
      <router-link to="/admin-dashboard" class="text-black bolder"
        >VOLVER A LA DASHBOARD</router-link
      >
    </nav>
    <RouterView />
  </div>
</template>
