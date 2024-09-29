<template>
    <el-text
        v-show="!error.example"
        v-for="(item, index) in error"
        :key="index"
        class="mx-1"
        type="danger"
        >{{ item }}</el-text
    >
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
