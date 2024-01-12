<template>
    <v-modal
        :target="'_R_' + role.role.trim()"
        styles="btn-sm btn-danger"
        button_cancel_name="Abortar operacion"
        :button_accept_show="false"
    >
        <template v-slot:button>Remover</template>
        <template v-slot:head>
            <span class="text-warning text-uppercase h3"> Remover scope </span>
        </template>
        <template v-slot:body>
            <p class="fw-bold text-uppercase text-info mb-2 h4">
                El siguiente Scope sera eliminado
            </p>
            <p class="fw-bold text-uppercase h5">
                <strong>{{ role.role }}:</strong> {{ role.descripcion }}
            </p>
            <span class="h6 text-danger mt-3 text-uppercase">
                Por favor ten mucha precaución al eliminar un scope. Eliminar
                puede hacer que el sistema funcione incorrectamente y esta
                opcion solo debe usarse si has ingresado por error un scope.
            </span>
            <br />
            <button
                class="btn btn-primary my-4"
                @click="remove(role)"
                data-bs-dismiss="modal"
            >
                Eliminar
            </button>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["scopeWasRemove"],

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
                    this.$emit("scopeWasRemove", res.data.data);
                })
                .catch((e) => {
                    console.error(e.response);
                });
        },
    },
};
</script>
