<template>
    <v-modal
        :target="'XXX_'.concat(item.canal)"
        styles="btn btn-sm btn-danger"
        :button_accept_show="false"
    >
        <template v-slot:button> remove </template>
        <template v-slot:head>
            <span>Remove Channel</span>
        </template>
        <template v-slot:body>
            <div class="text-center">
                <p>
                    Are you sure to destroy this channel?
                </p>

                <button
                    class="mt-4 btn-primary btn"
                    @click="remove(item)"
                    data-bs-dismiss="modal"
                >
                    Aceptar
                </button>
            </div>
        </template>
    </v-modal>
</template>
<script>
export default {
    props: ["item"],

    emits: ["success"],

    methods: {
        remove(item) {
            this.$server
                .delete(item.links.destroy)
                .then((res) => {
                    this.$emit("success", res.data.data);
                })
                .catch((e) => {
                    console.error(e.response);
                });
        },
    },
};
</script>
