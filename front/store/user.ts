import { computed, reactive } from 'vue'
import { Permission } from '../enum/Permission'
import { User } from '../interfaces/User'

const state = reactive({ user: null as User | null })

const setUser = (user: User | null) => {
  state.user = user
}

const user = computed(() => state.user)

function checkPermission(permission: Permission) {
  // TODO: this should be better
  const isInnerPermission = permission.includes('reviews')
  const escapedPermission = permission.replaceAll('.', '\\.')
  const regex = isInnerPermission ? new RegExp(`app\..+\.${escapedPermission}`) : escapedPermission
  return user.value?.permissions.some((p) => p.match(regex))
}

export const useUser = () => ({
  user,
  checkPermission,
  setUser,
})
