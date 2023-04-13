export interface Review {
  id: string
  pros: string[]
  cons: string[]
  rating: number
  message: string
  author: ReviewAuthor
}

export interface ReviewAuthor {
  id: null | string
  name: string
  avatar: null | string
}
