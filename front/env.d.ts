/// <reference types="vite/client" />

interface ImportMetaEnv {
  readonly VITE_SERVICE_API_URL: string
  readonly VITE_CORE_API_URL_FALLBACK: string
  // more env variables...
}

interface ImportMeta {
  readonly env: ImportMetaEnv
}
