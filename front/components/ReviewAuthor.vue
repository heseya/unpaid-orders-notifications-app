<template>
  <div class="review-author">
    <img
      v-if="author.avatar"
      :src="author.avatar"
      class="review-author__image"
      role="presentation"
    />
    <span class="review-author__name">{{ author.name }}</span>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue'
import { ReviewAuthor } from '../interfaces/Review'

export default defineComponent({
  name: 'ReviewAuthor',
  props: {
    author: {
      type: Object as PropType<ReviewAuthor>,
      required: true,
    },
  },
})
</script>

<style lang="scss" scoped>
.review-author {
  display: flex;
  align-items: center;

  &__image {
    width: 32px;
    height: 32px;
    object-fit: cover;
    border-radius: 4px;
    margin-right: 8px;
    background-color: #eee;
  }

  &__name {
    font-weight: bold;
  }
}
</style>
