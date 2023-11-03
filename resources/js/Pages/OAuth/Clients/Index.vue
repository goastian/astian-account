<template>
    <div class="container-fluid">
        <v-register-client @client-registered="getClients"></v-register-client>

        <v-table :items="items" class="text-sm table-sm text-center py-0" style="width: 70%; margin: auto;">
            <template v-slot:body>
                <tr
                    v-for="(item, index) in clients"
                    :key="index"
                    class="align-middle"
                >
                    <td>
                        {{ item.name }}
                    </td>
                    <td>
                        {{ item.redirect }}
                    </td>
                    <td class=" ">
                        <button
                            class="btn btn-primary btn-sm"
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
    </div>
</template>
<script>
import VRegisterClient from "./RegisterClient.vue"
import VRemove from "./RemoveClient.vue" 
import VUpdate from "./UpdateClient.vue"

export default {
    components: { 
        VRegisterClient,
        VRemove,
        VUpdate
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

        redirectForAuthorize(item) {
            window.open(item.redirect);
        },
    },
};
</script>
<style></style>
