<template>
  <div class="loading" v-if="isLoading">
      <a-spin />
  </div>

  <div v-if="!isLoading">
    <a-page-header
      :title="feed.name"
      :sub-title="feed.refreshed_at ? `last refreshed: ` + feed.refreshed_at : 'not generated yet'"
      @back="() => $router.push({ name: 'Index' })"
    >
      <template #extra>
        <a-button v-if="feed.url" @click="copy(feed.url)">Copy url</a-button>
        <a-button v-if="feed.id" @click="deleteFeed(feed.id)" type="danger">Delete</a-button>
      </template>
    </a-page-header>

    <a-form :model="feed" :label-col="{ span: 2 }" :wrapper-col="{ span: 21 }">
      <a-form-item label="Name">
        <a-input v-model:value="feed.name" required />
      </a-form-item>
      <a-form-item label="Auth">
        <a-radio-group v-model:value="feed.auth">
          <a-radio-button value="no">No auth</a-radio-button>
          <a-radio-button value="basic">Basic auth</a-radio-button>
        </a-radio-group>
      </a-form-item>
      <a-form-item label="Username" v-if="feed.auth === 'basic'">
        <a-input v-model:value="feed.username" />
      </a-form-item>
      <a-form-item label="Password" v-if="feed.auth === 'basic'">
        <a-input v-model:value="feed.password" />
      </a-form-item>
      <a-form-item label="Query">
        <a-input v-model:value="feed.query" required />
      </a-form-item>
      <a-form-item label="Fields">
        <a-textarea v-model:value="feed.fields" :auto-size="{ minRows: 16 }" required />
      </a-form-item>
      <a-form-item label="Save">
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
    const isLoading = ref(true)
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
          auth: feed.auth,
          username: feed.username,
          password: feed.password,
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
</style>
