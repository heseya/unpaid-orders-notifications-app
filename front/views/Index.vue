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
    <a-table-column key="auth" title="Auth" data-index="auth" #default="{ record }">
      {{ record.auth === 'no' ? 'no auth' : 'basic' }}
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
import axios from "axios";
import { Feed } from "../interfaces/Feed";

export default defineComponent({
  name: 'Index',
  setup() {
    const feeds = ref<Feed[]>([])
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
      } catch (error) {
        console.error(error)
        if (axios.isAxiosError(error)) {
          message.error(`Error: ${error.response.data.error.message}`)
        } else {
          message.error(`Unexpected Error`)
        }
      }
      isLoading.value = false
    }

    const newFeed = async () => {
      try {
        const { data } = await api.post(
          `/feeds`,
          {
            name: 'New feed',
            auth: 'no',
            query: '/products',
            fields: { title: 'name' },
          }
        )
        message.success('Created')
        await router.push({ name: 'Feed', params: { id: data.data.id } })

      } catch (error) {
        console.error(error)
        if (axios.isAxiosError(error)) {
          message.error(`Error: ${error.response.data.error.message}`)
        } else {
          message.error(`Unexpected Error`)
        }
      }
    }

    watch(page, () => getFeeds(), { immediate: true })

    return {
      newFeed,
      feeds,
      isLoading,
      pagination,
    }
  },
})
</script>
