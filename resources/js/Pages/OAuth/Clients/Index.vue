<template>
    <div class="container-fluid">
        <v-register-client @client-registered="getClients"></v-register-client>

        <v-table :items="items" styles="text-sm table-sm text-center py-0">
            <template v-slot:body>
                <tr v-for="(item, index) in clients" :key="index">
                    <td>
                        {{ item.name }}
                    </td>
                    <td>
                        {{ item.redirect }}
                    </td>
                    <td>
                        <v-authorize :client="item"></v-authorize>
                    </td>
                    <td>
                        <v-remove
                            :client="item"
                            @clientWasRemove="getClients"
                        ></v-remove>
                    </td>
                    <td>
                        <v-update :client="item"></v-update>
                    </td>
                    <td>
                        <v-token :client="item"></v-token>
                    </td>
                    <td>
                        <v-refresh-token :client="item"></v-refresh-token>
                    </td>
                </tr>
            </template>
        </v-table>
    </div>
</template>
<script>
import VRegisterClient from "./RegisterClient.vue";
import VTable from "../Components/table.vue";
import VUpdate from "./UpdateClient.vue";
import VToken from "./CreateToken.vue";
import VRefreshToken from "./RefreshToken.vue";
import VRemove from "./RemoveClient.vue";
import VAuthorize from './Authorization.vue'

export default {
    components: {
        VTable,
        VToken,
        VRefreshToken,
        VUpdate,
        VRegisterClient,
        VRemove,
        VAuthorize,
    },

    data() {
        return {
            items: ["name", "redirect"],
            clients: {},
        };
    },

    created() {
        this.getClients();
    },

    methods: {
        getClients() {
            window.axios
                .get("/oauth/clients")
                .then((res) => {
                    this.clients = res.data;
                })
                .catch((e) => {
                    console.log(e);
                });
        },
    },
};
</script>
<style></style>
