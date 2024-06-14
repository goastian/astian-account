<template>
    <v-table>
        <template v-slot:title>List of generated tokens</template>
        <template v-slot:head>
            <th>name</th>
            <th>created</th>
            <th>expires</th>
        </template>
        <template v-slot:body>
            <tr v-for="(item, index) in tokens" :key="index">
                <td v-text="item.name"></td>
                <td v-text="item.created_at"></td>
                <td v-text="item.expires_at"></td>
                <td>
                    <v-token-remove :token="item" @token-revoked="getTokens">
                    </v-token-remove>
                </td>
            </tr>
        </template>
    </v-table>
</template>
<script>
import VTokenRemove from "./TokenRemove.vue";

export default {
    components: {
        VTokenRemove,
    },

    data() {
        return {
            tokens: {},
        };
    },

    created() {
        this.getTokens();
    },

    mounted() {
        this.listenEvents();
    },

    methods: {
        getTokens() {
            this.$server
                .get("/oauth/tokens")
                .then((res) => {
                    this.tokens = res.data;
                })
                .catch((e) => {});
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_1(this.$id))
                .listen("RevokeCredentialsEvent", (e) => {
                    this.getTokens();
                });
        },
    },
};
</script>
<style lang="scss">
th {
    color: var(--first-color);
    text-align: start;
    text-transform: capitalize;
}

tr {
    color: var(--first-color);
    text-align: start;
    text-transform: capitalize;
}
</style>
