<template>
    <v-modal
        :target="`_2_${client.id}`"
        @is-accepted="deleteClient(item, $event)"
        styles="bg-danger btn-sm"
        :button_accept_show="false"
    >
        <template v-slot:button> Remove </template>
        <template v-slot:head>
            <span class="h4">Remove Client</span>
        </template>
        <template v-slot:body>
            <div class="text-md">
                <h3 class="text-danger">
                    Are you sure you want to delete this client?
                </h3>
                <h5 class="">
                    This action can't be reversed once it has been performed
                </h5>
                <button
                    class="btn mt-5 btn-danger btn-block"
                    @click="deleteClient(client, $event)"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    Procced
                </button>
            </div>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["clientWasRemove"],

    props: {
        client: {
            type: Object,
            required: true,
        },
    },

    methods: {
        deleteClient(client, event) {
            const button = event.target;
            button.disabled = true;

            this.$server
                .delete("/oauth/clients/" + client.id)
                .then((res) => {
                    this.$emit("clientWasRemove", res.data);
                    button.disabled = false;
                })
                .catch((e) => {
                    button.disabled = false;
                });
        },
    },
};
</script>
<style></style>
