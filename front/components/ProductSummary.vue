<template>
  <div class="product-summary" v-if="product">
    <img
      v-if="product.cover"
      :src="product.cover.url"
      role="presentation"
      class="product-summary__cover"
    />

    <div class="product-summary__content">
      <h1> Recenzje produktu: {{ product.name }} </h1>
      <p class="product-summary__rating">
        Średnia ocen:
        <b v-if="product.average_rating">{{ Math.round(product.average_rating * 10000) / 100 }}%</b>
        <b v-else> -brak- </b>
      </p>
      <p class="product-summary__rating">
        Liczba recenzji: <b>{{ product.reviews_count }}</b>
      </p>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue'

import { Product } from '../interfaces/Product'

export default defineComponent({
  props: {
    product: {
      type: Object as PropType<Product | null>,
      default: null,
    },
  },
})
</script>

<style lang="scss" scoped>
.product-summary {
  display: flex;
  align-items: center;
  margin-bottom: 20px;

  &__cover {
    width: 128px;
    height: 128px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0px 2px 6px #0000000b;
  }

  &__content {
    display: flex;
    flex-direction: column;
    margin-left: 16px;

    h1 {
      margin-top: 0;
      margin-bottom: 8px;
    }

    p {
      margin: 0;
    }
  }
}
</style>
