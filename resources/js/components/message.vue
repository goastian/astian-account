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
    position: absolute;
    background-color: #71cce8;
    top: 21%;
    z-index: 0;
    text-align: center;
    padding: 5%;
    border-radius: 1%;
    color: #262525f5;
    width: 66%;
}
</style>
