<template>
  <div class="debug" v-if="showDebug">
    <p>
      CoreURL: <i>{{ coreUrl || '-none-' }}</i>
    </p>
    <p>
      Access token: <i>{{ token || '-none-' }}</i>
    </p>
    <p>
      User: <i>{{ user?.name || '-none-' }}</i>
    </p>
    <button @click="refreshIdentityToken">Request token refresh</button>
  </div>
</template>

<script lang="ts">
import { computed, defineComponent } from 'vue'
import { openCommunicationChannel } from 'bout'

import { useUser } from '../store/user'
import { useIdentityToken } from '../store/token'
import { useCoreApiUrl } from '../store/coreApiUrl'

import { User } from '../interfaces/User'

export default defineComponent({
  name: 'App',
  setup() {
    const { user } = useUser()
    const { token } = useIdentityToken()
    const { coreUrl } = useCoreApiUrl()

    const tokenChannel = openCommunicationChannel('Token')

    const refreshIdentityToken = () => {
      tokenChannel.emit('refresh')
    }

    const showDebug = computed(() => (coreUrl.value ? coreUrl.value.includes('localhost') : true))

    return {
      user,
      token,
      coreUrl,
      showDebug,
      refreshIdentityToken,
    }
  },
})
</script>

<style lang="scss" scoped>
.debug {
  position: fixed;
  right: 10px;
  bottom: 10px;
  background-color: #fff;
  border: solid 2px firebrick;
  padding: 8px;
  max-width: 300px;
  font-size: 0.7em;
  // display: none;
}
</style>
