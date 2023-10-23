<template>
    <v-modal
        :target="`_1_${client.id}`"
        @is-accepted="updateClient(client.id)"
        styles="bg-warning btn-sm"
        :button_accept_show="false"
    >
        <template v-slot:button> update </template>
        <template v-slot:head>
            <span class="h3"> Actualizar datos del cliente </span>
        </template>
        <template v-slot:body>
            <div class="class row row-cols-3 col-sm-12">
                <div class="col-4">
                    <label for="cliente">cliente</label>
                    <input 
                        type="text"
                        v-model="client.name"
                        class="form-control"
                        @keyup.enter="updateClient(client.id)"
                    />
                </div>
                <div class="col-8">
                    <label for="redirect">redirect</label>
                    <input
                        type="text"
                        v-model="client.redirect"
                        id="redirect"
                        class="form-control"
                        @keyup.enter="updateClient(client.id)"
                    />
                </div>
            </div>
            <div
                v-show="message"
                class="row py-2 my-2 text-center row-cols-1 col-12 bg-success"
            >
                <span>{{ message }}</span>
            </div>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["isUpdated"],

    props: {
        client: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            message: "",
            errors: {},
        };
    },

    methods: {
        sendEventIsUpdated() {
            this.$emit("isUpdated", this.client);
        },

        updateClient(id) {
            this.message = null;
            window.axios
                .put("/oauth/clients/" + id, this.client)
                .then((res) => {
                    this.message = "datos actualizados";
                    this.sendEventIsUpdated();
                })
                .catch((e) => {
                    console.log(e);
                });
        },
    },
};
</script>
<style></style>
