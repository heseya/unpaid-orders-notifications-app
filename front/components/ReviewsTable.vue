<template>
  <a-table
    class="reviews-table"
    :dataSource="reviews"
    :loading="isLoading"
    rowKey="id"
    :locale="{ emptyText: 'Brak recenzji dla danego produktu' }"
  >
    <a-table-column key="author" title="Autor" data-index="author" #default="{ text }">
      <review-author :author="text" />
    </a-table-column>
    <a-table-column key="message" title="Treść recenzji" data-index="message" />
    <a-table-column key="rating" title="Ocena" data-index="rating" #default="{ text }">
      {{ Math.round(text * 10000) / 100 }}%
    </a-table-column>
    <a-table-column key="pros" title="Plusy" data-index="pros" #default="{ text }">
      <ul class="pros-cons-list">
        <li v-for="(pro, i) in text" :key="i">{{ pro }}</li>
      </ul>
    </a-table-column>
    <a-table-column key="cons" title="Minusy" data-index="cons" #default="{ text }">
      <ul class="pros-cons-list pros-cons-list--cons">
        <li v-for="(con, i) in text" :key="i">{{ con }}</li>
      </ul>
    </a-table-column>
    <a-table-column key="media" title="Media" data-index="media" #default="{ text }">
      <div class="media-list hes-gallery">
        <img class="media-list__image" v-for="media in text" :key="media.id" :src="media.url" />
      </div>
    </a-table-column>
    <a-table-column key="actions" #default="{ record }">
      <div class="action-buttons">
        <a-popconfirm
          title="Czy na pewno chcesz usunąć tę recenzję?"
          ok-text="Usuń"
          ok-type="danger"
          cancel-text="Anuluj"
          :disabled="!canRemove"
          @confirm="$emit('delete', record.id)"
        >
          <a-button size="small" :disabled="!canRemove"> Usuń </a-button>
        </a-popconfirm>
      </div>
    </a-table-column>
  </a-table>
</template>

<script lang="ts">
import { computed, defineComponent, PropType, watch } from 'vue'
import HesGallery from 'hes-gallery'

import { Review } from '../interfaces/Review'
import { useUser } from '../store/user'
import { Permission } from '../enum/Permission'

export default defineComponent({
  emits: ['delete'],
  props: {
    reviews: {
      type: Array as PropType<Review[]>,
      required: true,
    },
    isLoading: {
      type: Boolean,
      default: false,
    },
  },
  setup(props) {
    const { checkPermission } = useUser()

    const canRemove = computed(() => checkPermission(Permission.ReviewsRemove))

    watch(
      () => props.isLoading,
      (isLoading) => {
        if (!isLoading) {
          HesGallery.init()
        }
      }
    )

    return {
      canRemove,
    }
  },
})
</script>

<style lang="scss" scoped>
.reviews-table:deep(.ant-table-row td) {
  padding: 8px;
}

.pros-cons-list {
  margin: 0;
  padding-left: 0;
  list-style: none;

  li {
    padding-left: 16px;
    position: relative;
  }

  li::before {
    content: '+';
    position: absolute;
    left: 0;
    top: -1px;
    font-weight: 600;
    margin-right: 8px;
  }

  &--cons li::before {
    content: '-';
  }
}

.media-list {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  grid-gap: 6px;

  &__image {
    width: 32px;
    height: 32px;
    object-fit: cover;
    border-radius: 4px;
    cursor: pointer;
  }
}

.action-buttons {
  display: flex;
  gap: 8px;
}
</style>
