import { computed, reactive } from 'vue'

const state = reactive({ token: null as string | null })

const setToken = (token: string) => {
  state.token = token
}

const token = computed(() => state.token)

export const useIdentityToken = () => ({
  setToken,
  token,
})
