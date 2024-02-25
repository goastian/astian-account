<template>
    <v-create @token-was-created="getPersonalAccessToken"></v-create>
    <v-table :items="head">
        <template v-slot:body>
            <tr v-for="(item, index) in tokens" :key="index">
                <td>{{ item.name }}</td>
                <td>{{ item.scopes.join(",") }}</td>
                <td>{{ item.created_at }}</td>
                <td>{{ item.expires_at }}</td>
                <td>
                    <v-remove
                        :token="item"
                        @token-was-remove="getPersonalAccessToken"
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
            head: ["name", "scopes", "created", "expires"],
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
                .private(this.$channels.ch_1(window.$id))
                .listen("RevokeCredentialsEvent", (e) => {
                    this.getPersonalAccessToken();
                });
        },
    },
};
</script>
<style lang=""></style>
