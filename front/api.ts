import axios, { AxiosError } from 'axios'
import { openCommunicationChannel } from 'bout'
import isNull from 'lodash/isNull'

import { useIdentityToken } from './store/token'
import { useCoreApiUrl } from './store/coreApiUrl'

declare module 'axios' {
  interface AxiosRequestConfig {
    _retried?: boolean
  }
}

// Inserted on build time
const SERVICE_API_URL = import.meta.env.VITE_APP_URL

type OnRefreshFunction = (token: string | null) => void

export const createApiInstance = (baseURL: string) => {
  const apiInstance = axios.create({ baseURL })
  const tokenChannel = openCommunicationChannel('Token')

  const { token: identityToken } = useIdentityToken()
  const { coreUrl: CORE_API_URL } = useCoreApiUrl()

  tokenChannel.on('set', (token: string) => {
    isRefreshing = false

    onRefreshed(token)
    subscribers = []
  })

  let isRefreshing = false
  let subscribers: OnRefreshFunction[] = []

  const subscribeTokenRefresh = (cb: OnRefreshFunction) => {
    subscribers.push(cb)
  }

  const onRefreshed: OnRefreshFunction = (tokens) => {
    subscribers.map((cb) => cb(tokens))
  }

  apiInstance.interceptors.request.use((config) => {
    config.headers = { ...config.headers }

    const token = identityToken.value

    if (!isNull(token)) {
      config.headers.Authorization = `Bearer ${token}`
    }

    config.headers['X-Core-Url'] = CORE_API_URL.value === 'http://localhost' ?
        'http://host.docker.internal:80' :
        CORE_API_URL.value

    return config
  })

  apiInstance.interceptors.response.use(undefined, async (error: AxiosError) => {
    const originalRequest = error.config

    if (error.response?.status === 403) {
      // TODO: handle 403 error
    }

    if (error.response?.status === 401 && !originalRequest._retried) {
      // ? This wil prevent the second refresh if request fails twice
      originalRequest._retried = true

      if (!isRefreshing) {
        isRefreshing = true
        tokenChannel.emit('refresh')
      }

      return new Promise((resolve) => {
        subscribeTokenRefresh((refreshedToken) => {
          // ? If token not refreshed, logout & redirect to login
          if (!refreshedToken) {
            throw error
          }

          originalRequest.headers = {
            ...originalRequest.headers,
            Authorization: `Bearer ${refreshedToken}`,
          }

          // ? Retry last request
          resolve(apiInstance.request(originalRequest))
        })
      })
    }

    throw error
  })

  return apiInstance
}

export const api = createApiInstance(SERVICE_API_URL)
