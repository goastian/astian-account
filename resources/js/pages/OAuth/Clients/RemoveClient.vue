<template>
    <v-confirm bg="btn-secondary" @is-confirmed="deleteClient(client)">
        <template v-slot:button>Remove</template>
        <template v-slot:head>
            <strong class="text-xl"> Delete client {{ client.name }}</strong>
        </template>
        <template v-slot:body>
            <p class="text-xl">
                Are you share you want to remove this client with ID
                {{ client.id }} ?
            </p>
        </template>
    </v-confirm>
</template>
<script>
export default {
    emits: ["clientRemoved"],

    props: {
        client: {
            type: Object,
            required: true,
        },
    },

    methods: {
        deleteClient(client) {
            this.$server
                .delete("/oauth/clients/" + client.id)
                .then((res) => {
                    this.$emit("clientRemoved", res.data);
                })
                .catch((e) => {});
        },
    },
};
</script>
<style lang="scss" scoped></style>
