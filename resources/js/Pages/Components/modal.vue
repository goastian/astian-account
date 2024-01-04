<template>
    <button
        type="button"
        :class="['btn me-1 my-1', styles]"
        @click="sendEvent1(id)"
        data-bs-toggle="modal"
        :data-bs-target="'#'.concat(target)"
    >
        <slot name="button"></slot>
    </button>

    <!-- Modal -->
    <div
        :class="['modal', 'fade', width]"
        :id="target"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content text-light bg-dark">
                <div class="modal-header">
                    <slot name="head"></slot>
                </div>
                <div class="modal-body">
                    <slot name="body"></slot>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-success"
                        @click="sendEvent2(id)"
                        v-show="button_accept_show"
                    >
                        Aceptar
                    </button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        @click="sendEvent3(id)"
                    >
                        {{ button_cancel_name }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    emits: ["isClicked", "isAccepted", "isClosed"],

    props: {
        target: {
            type: [String, Array],
            required: true,
        },

        width: {
            type: String,
            default: "modal-lg",
        },
        styles: {
            type: [String, Array],
            default: "btn-sm btn-success",
        },
        button_accept_show: {
            type: Boolean,
            default: true,
        },
        button_cancel_name: {
            type: String,
            default: "Cerrar",
        },
    },

    methods: {
        sendEvent1(id) {
            this.$emit("isClicked", id);
        },

        sendEvent2(id) {
            this.$emit("isAccepted", id);
        },

        sendEvent3(id) {
            this.$emit("isClosed", id);
        },
    },
};
</script>
