<template>
    <span
        v-show="!error.example"
        v-for="(item, index) in error"
        :key="index"
        class="mx-1 text-red-500 block"
    >
        {{ item }}
    </span>
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
