<template>
    <v-table
        :items="items"
        class="text-center table-sm text-sm"
        style="width: 75%; margin: 0% auto"
        
    >
        <template v-slot:body>
            <tr v-for="(item, index) in tokens" :key="index">
                <td>{{ item.scopes.join(",") }}</td>
                <td>{{ item.name }}</td>
                <td>{{ item.created_at }}</td>
                <td>{{ item.expires_at }}</td>
                <td>
                    <v-token-remove :token="item" @token-was-revoked="getTokens">
                    </v-token-remove>
                </td>
            </tr>
        </template>
    </v-table>
</template>
<script>
import VTable from "../Components/table.vue";
import VTokenRemove from "./TokenRemove.vue";

export default {
    components: {
        VTable,
        VTokenRemove,
    },

    data() {
        return {
            items: ["scopes", "name", "created", "expires"],
            tokens: {},
        };
    },

    created() {
        this.getTokens();
    },

    methods: {
        getTokens() {
            window.axios
                .get("/oauth/tokens")
                .then((res) => {
                    this.tokens = res.data;
                })
                .catch((e) => {
                    console.log(e);
                });
        },

        storeToken() {
            window.axios.post("/oauth/tokens", this.form).then((res) => {});
        },
    },
};
</script>
<style lang=""></style>
