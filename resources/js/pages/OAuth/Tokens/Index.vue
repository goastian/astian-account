<template>
    <v-table :items="items" class="text-center text-sm">
        <template v-slot:body>
            <tr v-for="(item, index) in tokens" :key="index">
                <td>
                    <span class="px-1" v-for="(scope, index) in item.scopes"
                        >{{ scope }} <br v-if="index % 5 == 0" />
                    </span>
                </td>

                <td>{{ item.created_at }}</td>
                <td>{{ item.expires_at }}</td>
                <td>
                    <v-token-remove
                        :token="item"
                        @token-was-revoked="getTokens"
                    >
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
            items: ["scopes", "created", "expires"],
            tokens: {},
        };
    },

    created() {
        this.getTokens();
        this.listenEvents();
    },

    methods: {
        getTokens() {
            this.$server
                .get("/oauth/tokens")
                .then((res) => {
                    this.tokens = res.data;
                })
                .catch((e) => {
                    console.log(e);
                });
        },

        storeToken() {
            this.$server.post("/oauth/tokens", this.form).then((res) => {});
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_1(window.$id))
                .listen("RevokeCredentialsEvent", (e) => {
                    this.getTokens();
                });
        },
    },
};
</script>
<style lang=""></style>
