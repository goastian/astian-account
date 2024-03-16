<template>
    <div v-show="message" class="message">
        {{ message }}
        <i
            class="bi bi-x-lg d-block fw-bold mt-5"
            @click="close"
            style="cursor: pointer"
        ></i>
    </div>
</template>

<script>
export default {
    props: ["message"],
    emits: ["close"],
    methods: {
        close() {
            this.$emit("close");
        },
        onEscapeKey(event) {
            if (event.key === "Escape") {
                this.close();
            }
        },
    },
    mounted() {
        document.addEventListener("keydown", this.onEscapeKey);
    },
    beforeUnmount() {
        document.removeEventListener("keydown", this.onEscapeKey);
    },
};
</script>

<style lang="css" scoped>
.message {
    position: fixed;
    background-color: #597484;
    top: 20%;
    z-index: 1;
    text-align: center;
    padding: 5%;
    border-radius: 1%;
    color: #fff;
}
</style>
