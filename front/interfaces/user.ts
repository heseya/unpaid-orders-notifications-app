import { Permission } from '../enum/Permission'

export interface User {
  id: string
  email: string
  name: string
  avatar: string
  roles: unknown[]
  permissions: Permission[]
}
