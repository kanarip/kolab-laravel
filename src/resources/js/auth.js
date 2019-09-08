import bearer from '@websanova/vue-auth/drivers/auth/bearer'
import axios from '@websanova/vue-auth/drivers/http/axios.1.x'
import router from '@websanova/vue-auth/drivers/router/vue-router.2.x'

// Auth base configuration some of this options
// can be override in method calls
const config = {
  auth: bearer,
  http: axios,
  router: router,
  tokenDefaultName: 'kolab',
  tokenStore: ['localStorage'],
  registerData: {url: 'v4/users/register', method: 'POST', redirect: '/login'},
  loginData: {url: 'v4/users/login', method: 'POST', redirect: '', fetchUser: true},
  logoutData: {url: 'v4/users/logout', method: 'POST', redirect: '/', makeRequest: true},
  fetchData: {url: 'v4/users/user', method: 'GET', enabled: true},
  refreshData: {url: 'v4/users/refresh', method: 'GET', enabled: true, interval: 30}
}

export default config
