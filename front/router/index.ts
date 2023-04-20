import { createRouter, createWebHashHistory } from 'vue-router'

import Index from '../views/Index.vue'
import Feed from '../views/Feed.vue'

const routes = [
  {
    path: '/',
    name: 'Index',
    component: Index,
  },
  {
    path: '/feed/:id',
    name: 'Feed',
    component: Feed,
  },
]
const router = createRouter({
  history: createWebHashHistory(),
  routes,
})

export default router
