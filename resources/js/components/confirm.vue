<template>
    <button class="btn" :class="bg" @click="showMessage">
        <slot name="button"></slot>
    </button>

    <div v-if="message" class="message">
        <div class="box">
            <div class="head">
                <p>
                    <slot name="head"></slot>
                </p>
            </div>
            <div class="body">
                <slot name="body"></slot>
            </div>
            <div class="foot">
                <button class="btn btn-ternary" @click.prevent="acceptAction">
                    Yes
                </button>
                <button class="btn btn-secondary" @click.prevent="denyAcction">
                    No
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        bg: { required: false, type: String, default: "btn-primary" },
    },

    emits: ["isConfirmed", "isCancel", "isClicked"],
    data() {
        return {
            message: false,
        };
    },

    methods: {
        showMessage() {
            this.message = !this.message;
            this.$emit("isClicked", true);
        },

        acceptAction() {
            this.$emit("isConfirmed", true);
            this.message = !this.message;
        },

        denyAcction() {
            this.$emit("isCancel", false);
            this.message = !this.message;
        },
    },
};
</script>

<style lang="scss" scoped>
.message {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 1;

    .box {
        width: 90%;
        height: auto;
        margin: 30% auto;
        background-color: var(--white);
        color: var(--first-color);
        border: 1px solid var(--border-color-light);
        border-radius: 1em;

        @media (min-width: 800px) {
            margin: 10% auto;
            width: 80%;
        }

        @media (min-width: 940px) {
            width: 50%;
        }

        .head {
            text-align: center;
            border-bottom: 1px solid var(--border-color-light);
            min-height: 5vh;
            font-weight: bold;
        }

        .body {
            display: flex;
            text-align: center;
            justify-content: center;
            flex-direction: column;
            border-bottom: 1px solid var(--border-color-light);
            min-height: 10vh;
        }

        .foot {
            display: flex;
            justify-content: space-around;
            padding: 1em;
        }
    }
}
</style>
