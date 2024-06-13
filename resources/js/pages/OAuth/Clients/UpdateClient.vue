<template>
    <v-modal @is-accepted="updateClient(client.id)">
        <template v-slot:button> update </template>
        <template v-slot:body>
            <div class="box">
                <div class="item">
                    <label for="cliente">Client</label>
                    <input type="text" v-model="client.name" class="input" />
                    <v-error :error="errors.name"></v-error>
                </div>
                <div class="item">
                    <label for="redirect">Redirect</label>
                    <input
                        type="text"
                        v-model="client.redirect"
                        id="redirect"
                        class="input"
                    />
                    <v-error :error="errors.redirect"></v-error>
                </div>
            </div>
            <v-message :id="message_show">
                <template v-slot:body>
                    {{ message }}
                </template>
            </v-message>
        </template>
    </v-modal>
</template>
<script>
export default {

    emits: ["clientUpdated"],

    props: {
        client: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            message: null,
            message_show: null,
            errors: {
                name: "",
                redirect: "",
            },
        };
    },

    methods: {
        updateClient(id, event) {
            this.$server
                .put("/oauth/clients/" + id, this.client)
                .then((res) => {
                    this.message = "Client information updated";
                    this.message_show = Math.floor(Math.random() * 10000);
                    this.$emit("clientUpdated", res.data);
                })
                .catch((e) => {
                    if (e.response && e.response.status == 422) {
                        this.errors = e.response.data.errors;
                    }
                });
        },
    },
};
</script>
<style lang="scss" scoped>
.box {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 2%;

    .item {
        flex: 1 1 calc(100% / 2);
        color: var(--first-color);
        label {
            display: block;
        }
    }
}
</style>
