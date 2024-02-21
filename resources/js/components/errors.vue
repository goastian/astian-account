<template>
    <div
        v-show="!error.example"
        class="errors"
        v-for="(item, index) in error"
        :key="index"
    >
        <span v-text="item"></span>
    </div>
</template>
<script>
export default {
    props: {
        error: {
            type: [Object, String],
            required: true,
            default: { example: "example Error" },
        },
    },
};
</script>
<style lang="css" scoped>
.errors {
    color: #e36159;
}
</style>
