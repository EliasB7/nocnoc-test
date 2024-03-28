import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import SecretAdmin from '../components/secret-admin.vue'
import ForgotPassword from '../views/ForgotPassword.vue'
import ResetPassword from '../views/ResetPassword.vue'
import SuperAdminDashboard from '../views/SuperAdminDashboard.vue'
import SuperAdminRegister from '@/views/SuperAdminRegister.vue'
import CreateTask from '../views/CreateTask.vue'
import TaskList from '../views/TaskList.vue'
import UserRegister from '../views/UserRegister.vue'
import EditTask from '../views/EditTask.vue'
import TaskDetails from '@/views/TaskDetails.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },

    {
      path: '/tasks/:taskId/edit',
      name: 'EditTask',
      component: EditTask,
      meta: { requiresAuth: true } // Marcar la ruta que requiere autenticación
    },

    {
      path: '/admin-dashboard',
      name: 'admin-dashboard',
      component: SuperAdminDashboard,
      meta: { requiresSuperAdmin: true } // Marcar la ruta que requiere superadmin
    },

    {
      path: '/tasks/:taskId',
      name: 'TaskDetails',
      component: TaskDetails
    },

    {
      path: '/super-admin-register',
      name: 'SuperAdminRegister',
      component: SuperAdminRegister,
      meta: { requiresSuperAdmin: true } // Marcar la ruta que requiere superadmin
    },

    {
      path: '/user-register',
      name: 'UserRegister',
      component: UserRegister,
      meta: { requiresSuperAdmin: true } // Marcar la ruta que requiere superadmin
    },

    {
      path: '/createTask',
      name: 'CreateTask',
      component: CreateTask,
      meta: { requiresSuperAdmin: true } // Marcar la ruta que requiere superadmin
    },

    {
      path: '/tasks',
      name: 'TaskList',
      component: TaskList
    },

    {
      path: '/secret-admin',
      name: 'secret-admin',
      component: SecretAdmin
    },
    { path: '/forgot-password', component: ForgotPassword },
    {
      path: '/reset-password/:token',
      name: 'reset-password',
      component: ResetPassword
    }
  ]
})

router.beforeEach((to, from, next) => {
  const userType = localStorage.getItem('userType')
  const requiresSuperAdmin = to.matched.some((record) => record.meta.requiresSuperAdmin)

  if (requiresSuperAdmin && userType !== 'superadmin') {
    next({ name: 'TaskList' }) // Redirigir al inicio si el usuario no es superadmin
  } else {
    next()
  }
})

export default router
