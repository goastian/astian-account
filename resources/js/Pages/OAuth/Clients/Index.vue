<template>
    <div class="container-fluid">
        <v-register-client @client-registered="getClients"></v-register-client>

        <v-table
            :items="items"
            class="text-sm table-sm text-center py-0"
            style="width: 75%; margin: auto;"
        >
            <template v-slot:body>
                <tr v-for="(item, index) in clients" :key="index">
                    <td class="col-3">
                        {{ item.name }}
                    </td>
                    <td class="col-3">
                        {{ item.redirect }}
                    </td>
                    <td class="col-6">
                        <v-authorize :client="item"></v-authorize>

                        <v-remove
                            :client="item"
                            @clientWasRemove="getClients"
                        ></v-remove>

                        <v-update :client="item"></v-update>

                        <v-token :client="item"></v-token>

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
import VAuthorize from "./Authorization.vue";

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
