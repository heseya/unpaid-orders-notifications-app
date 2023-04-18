<template>
  <div class="loading" v-if="isLoading">
      <a-spin />
  </div>

  <div v-if="!isLoading">
    <a-page-header
      :title="feed.name"
      :sub-title="feed.refreshed_at ? `last refreshed: ${feed.refreshed_at}` : 'not generated yet'"
      @back="() => $router.push({ name: 'Index' })"
    >
      <template #extra>
        <a-button v-if="feed.url" @click="copy(feed.url)">Copy url</a-button>
        <a-button v-if="feed.id" @click="deleteFeed(feed.id)" type="danger">Delete</a-button>
      </template>
    </a-page-header>

    <a-form :model="feed">
      <a-form-item label="Name">
        <a-input v-model:value="feed.name" />
      </a-form-item>
      <a-form-item label="Query">
        <a-input v-model:value="feed.query" />
      </a-form-item>
      <a-form-item label="Fields">
        <a-textarea v-model:value="feed.fields" :auto-size="{ minRows: 16 }" />
      </a-form-item>
      <a-form-item class="text-right">
        <a-button type="primary" @click="submit(feed)">Save</a-button>
      </a-form-item>
    </a-form>
  </div>
</template>

<script lang="ts">
import {computed, defineComponent, ref} from 'vue'
import { useRoute } from 'vue-router'
import { api } from '../api'
import { message, Modal } from "ant-design-vue";
import router from "../router";

export default defineComponent({
  name: 'Feed',
  setup() {
    const route = useRoute()
    const feed = ref(null)
    const isLoading = ref(false)
    const feedId = computed(() => route.params.id as string)

    const getFeed = async () => {
      const { data } = await api.get(`/feeds/${feedId.value}`)
      feed.value = data.data
      feed.value.fields = JSON.stringify(feed.value.fields, null, 4)
    }

    Promise.all([getFeed()]).then(() => {
      isLoading.value = false
    })

    return {
      isLoading,
      feed,
    }
  },
  methods: {
    copy(url: String): void {
      navigator.clipboard.writeText(url)
      message.success('Copied')
    },
    submit(feed): void {
      let json = {}
      const saveFeed = async (json: object) => {
        await api.patch(`/feeds/${feed.id}`, {
          name: feed.name,
          query: feed.query,
          fields: json,
        })
      }

      try {
        json = JSON.parse(feed.fields)
      } catch {
        message.error('JSON is not valid')
        return
      }

      Promise.all([saveFeed(json)]).then(() => {
        message.success('Saved')
      })
    },
    deleteFeed(id: String): void {
      Modal.confirm({
        title: 'Do you Want to delete this feed?',
        async onOk() {
          const { status } = await api.delete(`/feeds/${id}`)

          if (status === 204) {
            message.success('Feed deleted')
            await router.push({name: 'Index'})
          } else {
            message.error('Error')
          }
        },
      })
    },
  },
})
</script>

<style scoped>
.loading {
    text-align: center;
    width: 100%;
    margin-top: 100px;
}

.text-right {
    text-align: right;
}
</style>
