<template>
    <div class="loading" v-if="isLoading">
        <a-spin />
    </div>

    <div v-if="!isLoading">
        <a-page-header
            :title="feed.name"
            :sub-title="feed.refreshed_at ? `last refreshed: ` + feed.refreshed_at : 'not generated yet'"
            @back="() => $router.go(-1)"
        >
            <template #tags>
                <a-tag color="blue" v-if="feed.is_refreshing">Refresing</a-tag>
            </template>
            <template #extra>
                <a-button @click="copy(feed.url)">Copy url</a-button>
                <a-button :disabled="feed.is_refreshing">Refresh now</a-button>
                <a-button type="danger">Delete</a-button>
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
                <a-button type="primary" html-type="submit">Save</a-button>
            </a-form-item>
        </a-form>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, ref } from 'vue'
import { useRoute } from 'vue-router'
import { api } from '../api'
import {message} from "ant-design-vue";

export default defineComponent({
  name: 'Feed',
  setup() {
    const route = useRoute()
    const feed = ref(null)
    const isLoading = ref(false)
    const feedId = computed(() => route.params.id as string)

    const getFeed = async () => {
      isLoading.value = true
      const { data } = await api.get(`/feeds/${feedId.value}`)
      feed.value = data.data
      feed.value.fields = JSON.stringify(feed.value.fields, null, 4)
    }

    Promise.all([getFeed()]).then(() => {
      isLoading.value = false
    })

    return {
      feed,
      isLoading,
    }
  },
  methods: {
    copy($url: String): void {
        navigator.clipboard.writeText($url);
        message.success('Copied!');
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
