<template>
    <v-create @token-created="getPersonalAccessToken"></v-create>
    <v-table v-if="tokens.length > 0">
        <template v-slot:head>
            <th>name</th>
            <th>scopes</th>
            <th>created</th>
            <th>expires</th>
        </template>
        <template v-slot:body>
            <tr v-for="(item, index) in tokens" :key="index">
                <td v-text="item.name"></td>
                <td v-text="item.scopes.join(', ')"></td>
                <td v-text="item.created_at"></td>
                <td v-text="item.expires_at"></td>
                <td>
                    <v-remove
                        :token="item"
                        @token-removed="getPersonalAccessToken"
                    ></v-remove>
                </td>
            </tr>
        </template>
    </v-table>
</template>
<script>
import VCreate from "./Create.vue";
import VRemove from "./Remove.vue";

export default {
    components: {
        VCreate,
        VRemove,
    },

    data() {
        return {
            tokens: {},
            user_id: null,
        };
    },

    created() {
        this.getPersonalAccessToken();
    },

    methods: {
        getPersonalAccessToken() {
            this.$server
                .get("/oauth/personal-access-tokens")
                .then((res) => {
                    this.tokens = res.data;
                })
                .catch((e) => {});
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_1(this.$id))
                .listen("RevokeCredentialsEvent", (e) => {
                    this.getPersonalAccessToken();
                });
        },
    },
};
</script>
<style lang="scss" scoped>
tr {
    td {
        &:nth-child(2) {
            max-width: 200px;
            min-width: 200px;
            word-wrap: break-word;
        }
    }
}
</style>
