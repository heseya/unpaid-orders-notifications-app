<template>
  <router-link class="product-return-link" to="/">
    <i class="bx bx-arrow-back"></i> Back to feeds list
  </router-link>

  <product-summary :product="product" />

  <reviews-table :reviews="reviews" :is-loading="isLoading" @delete="deleteReview" />
</template>

<script lang="ts">
import { computed, defineComponent, ref } from 'vue'
import { useRoute } from 'vue-router'
import { useToast } from 'vue-toastification'

import { api } from '../api'

import { Review } from '../interfaces/Review'
import { Product } from '../interfaces/Product'

import ReviewAuthor from '../components/ReviewAuthor.vue'
import ReviewsTable from '../components/ReviewsTable.vue'

export default defineComponent({
  name: 'Index',
  components: {
    ReviewAuthor,
    ReviewsTable,
  },
  setup() {
    const toast = useToast()
    const route = useRoute()
    const reviews = ref<Review[]>([])
    const editedReview = ref<Review | null>(null)

    const product = ref<Product | null>(null)
    const isLoading = ref(false)

    const productId = computed(() => route.params.id as string)

    const getProduct = async () => {
      isLoading.value = true
      const { data } = await api.get<Product>(`/products/${productId.value}`)
      product.value = data
    }

    const getReviews = async () => {
      isLoading.value = true
      try {
        // const response = await api.get<Review[]>('/reviews')
        const response = await api.get<Review[]>(`/reviews?product_id=${productId.value}`)
        reviews.value = response.data
      } catch (e) {
        console.error(e)
        toast.error('Wystąpił błąd podczas pobierania recenzji')
      }
    }

    const deleteReview = async (reviewId: string) => {
      try {
        await api.delete(`/reviews/${reviewId}`)
        reviews.value = reviews.value.filter((r) => r.id !== reviewId)

        toast.success('Recenzja została usunięta')
      } catch (e) {
        console.error(e)
        toast.error('Wystąpił błąd podczas usuwania recenzji')
      }
    }

    Promise.all([getProduct(), getReviews()]).then(() => {
      isLoading.value = false
    })

    product.value?.average_rating
    product.value?.reviews_count

    return {
      reviews,
      editedReview,
      deleteReview,
      product,
      isLoading,
    }
  },
})
</script>

<style lang="scss" scoped>
.product-return-link {
  margin-bottom: 16px;
  display: flex;
  align-items: center;

  i {
    margin-right: 8px;
  }
}
</style>
