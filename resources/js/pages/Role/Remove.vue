<template>
    <v-modal
        :target="'_R_' + role.role.trim()"
        styles="btn-sm btn-danger"
        button_cancel_name="Abortar operacion"
        :button_accept_show="false"
    >
        <template v-slot:button>Destroy</template>
        <template v-slot:body>
            <p class="fw-bold text-uppercase text-color mb-2 h4">
                The next scope will be destroyed
            </p>
            <p class="fw-bold text-uppercase h5">
                <strong>{{ role.role }}:</strong> {{ role.descripcion }}
            </p>
            <span class="h6 text-danger mt-3 text-uppercase">
                Please use caution when removing a scope. Deleting a scope may
                lead to system malfunction, and should only be done if a scope
                has been mistakenly entered
            </span>
            <br />
            <button
                class="btn btn-primary my-4"
                @click="remove(role)"
                data-bs-dismiss="modal"
            >
                Destroy
            </button>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["success", "errors"],

    props: {
        role: {
            type: Object,
            required: true,
        },
    },

    methods: {
        remove(role) {
            this.$server
                .delete(role.links.destroy)
                .then((res) => {
                    this.$emit("success", res.data.data);
                })
                .catch((e) => {
                    if (e.response) {
                        this.$emit("errors", e.response);
                    }
                });
        },
    },
};
</script>
