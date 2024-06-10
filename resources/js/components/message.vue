<template>
    <div v-if="message" class="message">
        <div class="body">
            <p class="color">
                <slot name="body"></slot>
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: [String, null, Number],
            required: true,
        },
    },

    data() {
        return {
            message: false,
        };
    },

    watch: {
        id(value) {
            this.showMessage(value);
        },
    },

    methods: {
        hideMessage() {
            this.message = false;
        },

        showMessage() {
            this.message = true;
            setTimeout(this.hideMessage, 5000);
        },
    },
};
</script>

<style lang="scss" scoped>
.message {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    min-height: 10vh;
    z-index: 1;
    background-color: #3a3b3b6b;
    pointer-events: none;

    .body {
        text-align: center;

        p {
            color: white;
            &::first-letter {
                text-transform: uppercase;
            }
        }
    }
}
</style>
