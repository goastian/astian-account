<template>
    <v-register-client @client-registered="getClients"></v-register-client>

    <v-table :items="items">
        <template v-slot:body>
            <tr
                v-for="(item, index) in clients"
                :key="index"
                class="align-middle"
            >
                <td>
                    {{ item.id }}
                </td>
                <td>
                    {{ item.secret }}
                </td>
                <td>
                    {{ item.name }}
                </td>
                <td>
                    {{ item.redirect }}
                </td>
                <td class=" ">
                    <button
                        class="btn btn-primary btn-sm mx-1"
                        @click="redirectForAuthorize(item)"
                    >
                        Authorizar
                    </button>

                    <v-remove
                        :client="item"
                        @clientWasRemove="getClients"
                    ></v-remove>

                    <v-update :client="item"></v-update>
                </td>
            </tr>
        </template>
    </v-table>
</template>
<script>
import VRegisterClient from "./RegisterClient.vue";
import VRemove from "./RemoveClient.vue";
import VUpdate from "./UpdateClient.vue";

export default {
    components: {
        VRegisterClient,
        VRemove,
        VUpdate,
    },

    data() {
        return {
            items: ["id", "secret", "name", "redirect"],
            clients: {},
        };
    },

    created() {
        this.getClients();
    },

    methods: {
        getClients() {
            this.$server
                .get("/oauth/clients")
                .then((res) => {
                    this.clients = res.data;
                })
                .catch((e) => {
                    console.log(e);
                });
        },

        redirectForAuthorize(item) {
            window.open(item.redirect);
        },
    },
};
</script>
<style></style>
