<template lang="">
    <div
        v-show="message"
        class="col-12 py-3 message fixed-top bg-danger text-capitalize text-center"
    >
        <span>{{ message }}</span>
        <button class="btn btn-warning mx-5 message-close" @click="close">X</button>
    </div>
</template>
<script>
export default {
    props: ["message"],

    emits: ["messageSend"],

    methods: {
        close() {
            this.$emit("messageSend");
        },
    },
};
</script>
<style lang="css" scoped>

.message {
    opacity: 0.7;
    color: rgb(255, 255, 254) !important;
}

.message-close {
    float: inline-end; 
    margin: 0;
    font-size: 1.5rem;

}
</style>
