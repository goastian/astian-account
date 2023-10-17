<template>
  <div class="table-responsive mt-3">
    <table :class="['table table-hover table-dark', styles]">
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
    styles: {
      type: [String, Array],
      required: false,
    },
    items: {
      type: Array,
      required: true,
    },
    /* title: {
            type: String,
            required: false,
            default: 'Data'
        }, 
        icon: {
            type: String,
            required: false,
            default: "fa-circle-arrow-left fa-2x fa-beat pull-right"
        },*/
    uri: {
      type: String,
      required: false,
      default: "home",
    },
  },
};
</script>
<styles scoped lang="css">
table {
  caption-side: top;
}
</styles>
