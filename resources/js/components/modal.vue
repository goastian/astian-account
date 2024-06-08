<template>
    <div class="window">
        <button class="btn" :class="bg" @click.prevent="openWindow">
            <slot name="button"></slot>
        </button>

        <div v-if="opened" class="window-content" id="window-content">
            <div class="content">
                <div class="head"><slot name="head"></slot></div>

                <div class="box">
                    <div class="body">
                        <slot name="body"></slot>
                    </div>

                    <div class="foot">
                        <button
                            v-if="accept"
                            class="btn btn-primary"
                            @click.prevent="sendAcceptEvent"
                        >
                            accept
                        </button>
                        <button
                            class="btn btn-secondary"
                            @click.prevent="closeWindow"
                        >
                            close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    emits: ["isClicked", "isAccepted", "isCancel"],

    props: {
        bg: {
            type: String,
            default: "btn-primary",
        },

        accept: {
            type: Boolean,
            default: true,
        },
    },

    data() {
        return {
            opened: false,
        };
    },

    methods: {
        closeWindow() {
            this.opened = false;
            this.sendClosedEvent(true);
        },

        openWindow() {
            this.opened = true;
            this.sendClickedEvet();
        },

        sendClickedEvet() {
            this.$emit("isClicked", true);
        },

        sendAcceptEvent() {
            this.$emit("isAccepted", true);
        },

        sendClosedEvent() {
            this.$emit("isClosed", true);
        },
    },
};
</script>

<style lang="scss" scoped>
.window-content {
    position: fixed;
    width: 100%;
    padding: 0.5em;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;

    .content {
        background-color: white;
        width: 95%;
        min-height: auto;
        border: 1px solid #cfc8c8;
        border-radius: 1em;
        @media (min-width: 800px) {
            margin: 0.5% auto;
            width: 95%;
        }
        @media (min-width: 940px) {
            width: 80%;
        }

        .head {
            color: var(--first-color);
            text-transform: uppercase;
            text-align: center;
            font-size: 1.3em;
            margin-bottom: 1%;
            border-bottom: 1px solid #cfc8c8;
        }

        .box {
            padding: 1em;
            // min-height: 20vh;
            max-height: 80vh;
            overflow-y: auto;
            border-radius: 1em;

            .body {
                width: 100%;
                min-height: 100%;
                border-bottom: 1px solid #cfc8c8;
                margin-bottom: 1em;
            }
            .foot {
                justify-content: space-between;
                display: flex;
                width: 100%;
            }
        }
    }
}
</style>
