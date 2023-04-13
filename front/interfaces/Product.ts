export interface Product {
  id: string
  name: string
  price: number
  price_min: number
  price_max: number
  public: boolean
  visible: boolean
  available: boolean
  quantity_step: number
  cover: {
    id: string
    type: string
    url: string
    alt: string
    slug: string
  }

  reviews_count: number
  average_rating: number
}
