<template>
    <v-search @searching="searching">
        <template v-slot:button>
            <v-register @success="getUsers"></v-register>
        </template>
    </v-search>

    <v-table :items="items">
        <template v-slot:body>
            <tr v-for="(item, index) in users" :key="index">
                <td class="fw-light">{{ item.name }}</td>
                <td class="fw-light">{{ item.last_name }}</td>
                <td class="fw-light">{{ item.email }}</td>
                <td>
                    <v-scopes :user="item" @success="getUsers"></v-scopes>
                    <v-update :user="item" @success="getUsers"></v-update>
                    <v-status
                        :user="item"
                        @success="getUsers"
                        @errors="alert"
                    ></v-status>
                </td>
            </tr>
        </template>
    </v-table>
    <v-pagination
        v-show="pages.total > pages.per_page"
        :pages="pages"
        @send-current-page="changeList"
    ></v-pagination>

    <v-message :message="message" @close="close"></v-message>
</template>
<script>
import VRegister from "./Register.vue";
import VUpdate from "./Update.vue";
import VStatus from "./Status.vue";
import VSearch from "./Search.vue";
import VScopes from "./Scopes.vue";

export default {
    components: {
        VRegister,
        VUpdate,
        VStatus,
        VSearch,
        VScopes,
    },

    data() {
        return {
            items: ["First Name", "Last Name", "Email Address"],
            users: {},
            pages: {},
            search: {
                page: 1,
            },
            message: null,
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

        alert(event) {
            if (event.status) {
                this.message = event.data.message;
            }
        },

        close() {
            this.message = null;
        },

        getUsers() {
            this.message = null;
            this.$server
                .get("/api/users", {
                    params: this.search,
                })
                .then((res) => {
                    this.users = res.data.data;
                    this.pages = res.data.meta.pagination;
                    this.search.page = res.data.meta.pagination.current_page;
                })
                .catch((e) => {
                    if (e.response && e.response.status == 403) {
                        this.message = e.response.data.message;
                    }
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
