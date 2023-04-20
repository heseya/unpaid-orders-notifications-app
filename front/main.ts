import { createApp } from 'vue'
import { createVue3MicroApp, isParentApp, registerMicroApp } from 'bout'
import AntDesignVue from 'ant-design-vue'

import router from './router/index'
import App from './App.vue'

import 'ant-design-vue/dist/antd.css'

const APP_NAME = 'Exporter'

const appFactory = () => {
  return createApp(App)
    .use(router)
    .use(AntDesignVue)
}

const microApp = createVue3MicroApp(APP_NAME, appFactory)
registerMicroApp(microApp)

// Mount to root if it is not a micro frontend
if (!isParentApp()) {
  const app = appFactory()
  app.mount('#app')
}
