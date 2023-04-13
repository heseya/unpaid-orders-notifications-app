import { createApp } from 'vue'
import { createVue3MicroApp, isParentApp, registerMicroApp } from 'bout'
import AntDesignVue from 'ant-design-vue'
import Toast, { PluginOptions } from 'vue-toastification'

import router from './router/index'
import App from './App.vue'

import 'ant-design-vue/dist/antd.css'
import 'vue-toastification/dist/index.css'

const APP_NAME = 'Exporter'

const appFactory = () => {
  return createApp(App)
    .use(router)
    .use(AntDesignVue)
    .use(Toast, { position: 'bottom-right' } as PluginOptions)
}

const microApp = createVue3MicroApp(APP_NAME, appFactory)
registerMicroApp(microApp)
console.log('Loaded child scrripts')

// Mount to root if it is not a micro frontend
if (!isParentApp()) {
  const app = appFactory()
  app.mount('#app')
}
