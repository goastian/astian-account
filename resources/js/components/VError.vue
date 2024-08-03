<template>
    <div
        v-show="!error.example"
        class="errors"
        v-for="(item, index) in error"
        :key="index"
    >
        <p v-text="item"> </p>
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
<style lang="scss" scoped>
.errors {
    color: #e36159;
    font-size: 0.8em;

    p {
        text-transform: lowercase;
        margin: 0;
        &::first-letter {
            text-transform: uppercase;
        }
    }
}
</style>
