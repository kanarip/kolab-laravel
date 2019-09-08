import bearer from '@websanova/vue-auth/drivers/auth/bearer'
import axios from '@websanova/vue-auth/drivers/http/axios.1.x'
import router from '@websanova/vue-auth/drivers/router/vue-router.2.x'

// Auth base configuration some of this options
// can be override in method calls
const config = {
  auth: bearer,
  http: axios,
  router: router,
  tokenDefaultName: 'laravel-vue-spa',
  tokenStore: ['localStorage'],
  rolesVar: 'role',
  registerData: {url: 'users/register', method: 'POST', redirect: '/login'},
  loginData: {url: 'users/login', method: 'POST', redirect: '', fetchUser: true},
  logoutData: {url: 'users/logout', method: 'POST', redirect: '/', makeRequest: true},
  fetchData: {url: 'users/user', method: 'GET', enabled: true},
  refreshData: {url: 'users/refresh', method: 'GET', enabled: true, interval: 30}
}

export default config
