<template>
  <div class="table-responsive mt-3">
    <table class="table table-hover table-dark">
      <thead>
        <th
          scope="col"
          class="text-uppercase"
          v-for="(item, index) in items"
          :key="index"
          v-text="item"
        ></th>
      </thead>
      <tbody>
        <slot name="body"></slot>
      </tbody>
    </table>
  </div>
</template>
<script>
export default {
  props: {     
    items: {
      type: Array,
      required: true,
    },  
  },
};
</script>
<styles scoped lang="css">
table {
  caption-side: top;
}
</styles>
