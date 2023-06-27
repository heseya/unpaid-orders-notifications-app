<template>
  <div class="loading" v-if="isLoading">
      <a-spin />
  </div>

  <div v-if="!isLoading">
    <a-page-header
      :title="feed.name"
      @back="() => $router.push({ name: 'Index' })"
      class="feed-header"
    >
      <template #extra>
        <a-button v-if="feed.url" @click="copy(feed.url)">Copy url</a-button>
        <a-button v-if="feed.id" @click="deleteFeed(feed.id)" type="danger">Delete</a-button>
      </template>
      <a-descriptions size="small" :column="3">
        <a-descriptions-item label="last refreshed">
          {{ feed.refreshed_at ? new Date(feed.refreshed_at).toLocaleString() : 'not generated yet' }}
        </a-descriptions-item>
        <a-descriptions-item label="Processed rows">
          {{ feed.processed_rows }}
        </a-descriptions-item>
      </a-descriptions>
    </a-page-header>

    <a-form :model="feed" :label-col="{ span: 3 }" :wrapper-col="{ span: 21 }">
      <a-form-item label="Name">
        <a-input v-model:value="feed.name" :rules="[{ required: true }]" />
      </a-form-item>
      <a-form-item label="Format">
        <a-radio-group v-model:value="feed.format">
          <a-radio-button value="csv">CSV</a-radio-button>
          <a-radio-button value="xml">XML</a-radio-button>
        </a-radio-group>
      </a-form-item>
      <a-form-item label="Auth">
        <a-radio-group v-model:value="feed.auth">
          <a-radio-button value="no">No auth</a-radio-button>
          <a-radio-button value="basic">Basic auth</a-radio-button>
        </a-radio-group>
      </a-form-item>
      <a-form-item label="Username" v-if="feed.auth === 'basic'">
        <a-input-password v-model:value="feed.username" password />
      </a-form-item>
      <a-form-item label="Password" v-if="feed.auth === 'basic'">
        <a-input-password v-model:value="feed.password" password />
      </a-form-item>
      <a-form-item label="Query">
        <a-input v-model:value="feed.query" :rules="[{ required: true }]" />
      </a-form-item>
      <a-form-item label="Fields">
        <a-textarea v-model:value="feed.fields" :auto-size="{ minRows: 16 }" :rules="[{ required: true }]" />
      </a-form-item>
      <a-form-item label="Help">
        <a-collapse ghost>
          <a-collapse-panel header="Available fields">
            <h3>Local</h3>
            <ul>
              <li><b>#cover</b> - url to first photo</li>
              <li><b>#additional_image</b> - url to second photo</li>
              <li><b>#availability</b> - in stock | out of stock</li>
              <li><b>#price</b> - price with currency</li>
              <li><b>#sale_price</b> - sale price with currency</li>
              <li><b>#ean</b> - ean from attributes</li>
            </ul>
            <h3>Global</h3>
            <ul>
              <li><b>@shipping_price</b> - lowest shipping price in Google format</li>
            </ul>
            <h3>Response</h3>
            <ul>
              <li><b>$string</b> - raw data from response</li>
            </ul>
            <h3>Raw</h3>
            <ul>
              <li><b>string</b> - raw string from input</li>
            </ul>
          </a-collapse-panel>
        </a-collapse>
      </a-form-item>
      <a-form-item label="Save">
        <a-button type="primary" @click="submit(feed)">Save</a-button>
      </a-form-item>
    </a-form>
  </div>
</template>

<script lang="ts">
import { computed, defineComponent, onBeforeMount, ref } from 'vue'
import { useRoute } from 'vue-router'
import { api } from '../api'
import { message, Modal } from "ant-design-vue";
import router from "../router";
import axios from "axios";
import { Feed } from "../interfaces/Feed";

export default defineComponent({
  name: 'Feed',
  setup() {
    const route = useRoute()
    const feed = ref<Feed | null>(null)
    const isLoading = ref(true)
    const feedId = computed(() => route.params.id as string)

    const getFeed = async () => {
      try {
        const {data} = await api.get(`/feeds/${feedId.value}`)
        feed.value = data.data
        feed.value.fields = JSON.stringify(feed.value.fields, null, 4)
      } catch (error) {
        console.error(error)
        if (axios.isAxiosError(error)) {
          message.error(`Error: ${error.response.data.error.message}`)
        } else {
          message.error('Unexpected error')
        }
      }
      isLoading.value = false
    }

    const copy = (url: String) => {
      navigator.clipboard.writeText(url)
      message.success('Copied')
    }

    const submit = async (feed) => {
      try {
        const json = JSON.parse(feed.fields)
        let data = {
          name: feed.name,
          query: feed.query,
          auth: feed.auth,
          fields: json,
        }

        if (feed.auth === 'basic') {
          data = {
            ...data,
            username: feed.username,
            password: feed.password,
          }
        }

        await api.patch(`/feeds/${feed.id}`, data)
        message.success('Saved')
      } catch (error) {
        console.error(error)
        if (axios.isAxiosError(error)) {
          message.error(`Error: ${error.response.data.error.message}`)
        } else {
          message.error('JSON is not valid')
        }
      }
    }

    const deleteFeed = (id: String) => {
      Modal.confirm({
        title: 'Do you want to delete this feed?',
        async onOk() {
          try {
            await api.delete(`/feeds/${id}`)
            message.success('Feed deleted')
            await router.push({name: 'Index'})

          } catch (error) {
            console.error(error)
            if (axios.isAxiosError(error)) {
              message.error(`Error: ${error.response.data.error.message}`)
            } else {
              message.error(`Unexpected Error`)
            }
          }
        },
      })
    }

    onBeforeMount(async () => await getFeed())

    return {
      copy,
      submit,
      deleteFeed,
      isLoading,
      feed,
    }
  },
})
</script>

<style scoped>
.loading {
    text-align: center;
    width: 100%;
    margin-top: 100px;
}

.feed-header {
  padding: 16px 0 !important;
}
</style>
