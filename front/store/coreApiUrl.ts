import { computed, reactive, ref } from 'vue'

const state = reactive({ coreApiUrl: import.meta.env.VITE_CORE_API_URL_FALLBACK })

const setCoreApiUrl = (url: string) => {
  console.log('Updated Core API URL to:', url)
  state.coreApiUrl = url
}

const coreUrl = computed(() => state.coreApiUrl)

export const useCoreApiUrl = () => ({
  coreUrl,
  setCoreApiUrl,
})
