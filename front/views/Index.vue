<template>
  <h1>Feeds</h1>

  <a-table
    :dataSource="products"
    rowKey="id"
    :customRow="customRow"
    :pagination="pagination"
    :loading="isLoading"
    :locale="{ emptyText: 'No feeds to show' }"
    class="products-table"
  >
    <a-table-column key="name" title="Name" data-index="name" #default="{ record }">
      <span>{{ record.name }}</span>
    </a-table-column>
    <a-table-column key="reviews_count" title="Last refresh" data-index="reviews_count" />
  </a-table>
</template>

<script lang="ts">
import { defineComponent, reactive, ref, watch } from 'vue'
import { useRouter } from 'vue-router'

import { api } from '../api'
import { Product } from '../interfaces/Product'

export default defineComponent({
  name: 'Index',
  setup() {
    const router = useRouter()
    const products = ref<Product[]>([])
    const isLoading = ref(false)

    // Pagination
    const page = ref(1)
    const pagination = reactive({
      value: page,
      pageSize: 12,
      total: 0,
      onChange: (current: number) => {
        page.value = current
      },
    })

    const getProducts = async () => {
      isLoading.value = true
      try {
      await api.post<{ data: Product[]; meta: { total: number } }>(
          `/feeds`,
          {
              'name': 'test',
              'query': '/products',
              'feed': '{"test":"test"}',

          }
      )

        const response = await api.get<{ data: Product[]; meta: { total: number } }>(
          `/feeds`
        )
        products.value = response.data.data
        pagination.total = response.data.meta.total
      } catch (e) {
        console.error(e)
      }
      isLoading.value = false
    }

    const customRow = (record: any) => {
      return {
        onClick: () => {
          router.push({ name: 'Product', params: { id: record.id } })
        },
      }
    }

    watch(page, () => {
      getProducts()
    })

    getProducts()

    return {
      products,
      customRow,
      isLoading,
      pagination,
    }
  },
})
</script>

<style lang="scss" scoped>
.product-cover-img {
  width: 32px;
  height: 32px;
  object-fit: cover;
  border-radius: 4px;
  margin-right: 8px;
  background-color: #eee;
}

.products-table:deep(.ant-table-row td) {
  padding: 8px;
}
</style>
