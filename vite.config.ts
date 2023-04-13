import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import Components from 'unplugin-vue-components/vite'
import { AntDesignVueResolver } from 'unplugin-vue-components/resolvers'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    Components({
      resolvers: [AntDesignVueResolver()],
    }),
  ],
  base: 'http://localhost/',
  publicDir: './front/public',
  build: {
    manifest: false,
    cssCodeSplit: false,
    outDir: './storage/app/front',
    lib: {
      name: 'Test',
      entry: './front/main.ts',
      fileName: 'bundle',
    },
  },
  optimizeDeps: {
    include: ['vue-toastification', 'hes-gallery'],
  },
})
