<template>
  <a-page-header title="Feeds">
    <template #extra>
      <a-button @click="newFeed">New feed</a-button>
    </template>
  </a-page-header>

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
      {{ record.refreshed_at ?? 'not generated yet' }}
    </a-table-column>
  </a-table>
</template>

<script lang="ts">
import { defineComponent, reactive, ref, watch } from 'vue'
import { api } from '../api'
import { message } from "ant-design-vue";
import router from "../router";

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
        const { data } = await api.get<{ data: []; meta: { total: number } }>(
          `/feeds`
        )
        feeds.value = data.data
        pagination.total = data.meta.total
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
  methods: {
    async newFeed(): void {
      const { data, status } = await api.post(
        `/feeds`,
        {
          name: 'New feed',
          query: '/products',
          fields: { title: 'name' },
        }
      )
      if (status === 201) {
        message.success('Created')
        await router.push({ name: 'Feed', params: {id: data.data.id} })
      } else {
        message.error('Error')
      }
    }
  },
})
</script>
