<template>
    <div>
        <v-register-client @client-registered="getClients"></v-register-client>

        <v-table>
            <template v-slot:title> List of clients </template>
            <template v-slot:head>
                <th>id</th>
                <th>secret key</th>
                <th>name</th>
                <th>redirect</th>
            </template>
            <template v-slot:body>
                <tr v-for="(item, index) in clients" :key="index">
                    <td v-text="item.id"></td>
                    <td v-text="item.secret"></td>
                    <td v-text="item.name"></td>
                    <td v-text="item.redirect"></td>
                    <td>
                        <div>
                            <v-remove
                                :client="item"
                                @client-removed="getClients"
                            ></v-remove>
                        </div>
                        <div>
                            <v-update
                                :client="item"
                                @client-updated="getClients"
                            ></v-update>
                        </div>
                    </td>
                </tr>
            </template>
        </v-table>
    </div>
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
                .catch((e) => {});
        },
    },
};
</script>
<style lang="scss" scoped>
div {
    width: 100%;
    padding: 0;
    margin-top: 1%;

    th {
        text-align: start;
        text-transform: capitalize;
        color: var(--first-color);
        font-size: 1em;
    }

    tr {
        text-align: start;
        color: var(--first-color);
        font-size: 1em;

        td {
            &:nth-child(5) {
                display: flex;
                justify-content: space-around;
                div {
                    padding: 0.1em;
                }
            }
        }
    }
}
</style>
