<template>
    <div class="container-fluid">
        <div class="mx-1">
            <v-search @searching="searching">
                <template v-slot:button>
                    <v-register @user-was-registered="getUsers"></v-register>
                </template>
            </v-search>

            <v-table :items="items" class="table-sm text-sm text-center">
                <template v-slot:body>
                    <tr v-for="(item, index) in users" :key="index">
                        <td>{{ item.nombre }}</td>
                        <td>{{ item.apellido }}</td>
                        <td>{{ item.correo }}</td>
                        <td>{{ item.telefono }}</td>
                        <td>{{ item.registrado }}</td>
                        <td>
                            <v-update
                                :user="item"
                                @user-was-updated="getUsers"
                            ></v-update>
                            <v-status
                                :user="item"
                                @user-status="getUsers"
                            ></v-status>
                        </td>
                    </tr>
                </template>
            </v-table>
            <v-pagination
                :pages="pages"
                @send-current-page="changeList"
            ></v-pagination>
        </div>
    </div>
</template>
<script>
import VRegister from "./Register.vue";
import VUpdate from "./Update.vue";
import VStatus from "./Status.vue";
import VSearch from "./Search.vue";

export default {
    components: {
        VRegister,
        VUpdate,
        VStatus,
        VSearch,
    },

    data() {
        return {
            items: ["nombre", "apellido", "correo", "telefono", "registrado"],
            users: {},
            pages: {},
            search: {
                page: 1,
            },
        };
    },

    beforeMount() {
        this.search.page = 1;
    },

    mounted() {
        this.getUsers();
        this.listenEvents();
    },

    methods: {
        searching(data) {
            this.search = Object.assign(this.search, data);
            this.getUsers();
        },

        getUsers() {
            window.axios
                .get("/api/users", {
                    params: this.search,
                })
                .then((res) => {
                    this.users = res.data.data;
                    this.pages = res.data.meta.pagination;
                    this.search.page = res.data.meta.pagination.current_page;
                })
                .catch((e) => {
                    console.log(e.response);
                });
        },

        changeList(id) {
            this.search.page = id;
            this.getUsers();
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("UpdateEmployeeEvent", (e) => {
                    this.getUsers();
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("StoreEmployeeEvent", (e) => {
                    this.getUsers();
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("EnableEmployeeEvent", (e) => {
                    this.getUsers();
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("DisableEmployeeEvent", (e) => {
                    this.getUsers();
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("StoreEmployeeRoleEvent", (e) => {
                    this.getUsers();
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyEmployeeRoleEvent", (e) => {
                    this.getUsers();
                });
        },
    },
};
</script>
