<template>
    <v-modal
        :target="`_1_${client.id}`"
        @is-accepted="updateClient(client.id)"
        styles="bg-warning btn-sm"
        :button_accept_show="false"
    >
        <template v-slot:button> update </template>
        <template v-slot:head>
            <span class="h3"> Update data </span>
        </template>
        <template v-slot:body>
            <div class="row">
                <div class="col">
                    <label for="cliente">Client</label>
                    <input
                        type="text"
                        v-model="client.name"
                        class="form-control"
                        @keyup.enter="updateClient(client.id)"
                    />
                    <v-error :error="errors.name"></v-error>
                </div>
                <div class="col">
                    <label for="redirect">Redirect</label>
                    <input
                        type="text"
                        v-model="client.redirect"
                        id="redirect"
                        class="form-control"
                        @keyup.enter="updateClient(client.id)"
                    />
                    <v-error :error="errors.redirect"></v-error>
                </div>
                <div class="col">
                    <button
                        class="btn float-start mt-3 btn-primary"
                        @click="updateClient(client.id)"
                    >
                        Update
                    </button>
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
            errors: {
                name: "",
                redirect: "",
            },
        };
    },

    methods: {
        sendEventIsUpdated() {
            this.$emit("isUpdated", this.client);
        },

        updateClient(id) {
            this.message = null;
            this.$server
                .put("/oauth/clients/" + id, this.client)
                .then((res) => {
                    this.message = "datos actualizados";
                    this.sendEventIsUpdated();
                })
                .catch((e) => {
                    if (e.response && e.response.data.errors) {
                        this.errors = e.response.data.errors;
                    }
                });
        },
    },
};
</script>
<style lang="scss" scoped>
.col {
    @media (min-width: 240px) {
        min-width: 98%;
        margin-bottom: 2%;
    }

    @media (min-width: 800px) {
        min-width: 48%;
        margin-bottom: 2%;
    }


    @media (min-width: 940px) {
        min-width: 30%;
        margin-bottom: 2%;
    }
 
}
</style>
