import { createRouter, createWebHashHistory } from 'vue-router'

import Index from '../views/Index.vue'
import Product from '../views/Product.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Index,
  },
  {
    path: '/product/:id',
    name: 'Product',
    component: Product,
  },
]
const router = createRouter({
  history: createWebHashHistory(),
  routes,
})

export default router
