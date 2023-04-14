<template>
    <h1>Feeds</h1>

    <a-table
    :dataSource="feeds"
    rowKey="id"
    :pagination="pagination"
    :loading="isLoading"
    :locale="{ emptyText: 'No feeds to show' }"
    >
        <a-table-column key="name" title="Name" data-index="name" #default="{ record }">
            <router-link :to="{ name: 'Feed', params: { id: record.id } }">
                {{ record.name }}
            </router-link>
        </a-table-column>
        <a-table-column key="refreshed_at" title="Last refresh" data-index="refreshed_at" #default="{ record }">
            {{ record.refreshed_at ?? 'no avaiable yet' }}
        </a-table-column>
    </a-table>
</template>

<script lang="ts">
import { defineComponent, reactive, ref, watch } from 'vue'
import { api } from '../api'

export default defineComponent({
  name: 'Index',
  setup() {
    const feeds = ref<[]>([])
    const isLoading = ref(false)

    // Pagination
    const page = ref(1)
    const pagination = reactive({
      value: page,
      pageSize: 24,
      total: 0,
      onChange: (current: number) => {
        page.value = current
      },
    })

    const getFeeds = async () => {
      isLoading.value = true
      try {
      // await api.post<{ data: Product[]; meta: { total: number } }>(
      //     `/feeds`,
      //     {
      //         'name': 'test',
      //         'query': '/products',
      //         'fields': {"test":"test", "test1":"test2"},
      //     }
      // )

        const response = await api.get<{ data: []; meta: { total: number } }>(
          `/feeds`
        )
        feeds.value = response.data.data
        pagination.total = response.data.meta.total
      } catch (e) {
        console.error(e)
      }
      isLoading.value = false
    }

    watch(page, () => {
      getFeeds()
    })

    getFeeds()

    return {
      feeds,
      isLoading,
      pagination,
    }
  },
})
</script>
