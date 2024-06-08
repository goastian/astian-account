<template>
    <v-table>
        <template v-slot:title>List of users</template>
        <template v-slot:head>
            <th>name</th>
            <th>last name</th>
            <th>email</th>
            <th>phone</th>
            <th>
                <v-register
                    @success="getUsers"
                    bg="btn-blue"
                ></v-register>
            </th>
        </template>
        <template v-slot:body>
            <tr v-for="(item, index) in users" :key="index">
                <td v-text="item.name"></td>
                <td v-text="item.last_name"></td>
                <td v-text="item.email"></td>
                <td v-text="item.full_phone"></td>
                <td>
                    <v-scopes :user="item" bg="btn-primary"></v-scopes>
                    <v-update
                        :user="item"
                        @success="getUsers"
                        bg="btn-secondary"
                    ></v-update>
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
                .get("/api/admin/users", {
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
                    if (e.response && e.response.status == 401) {
                        window.location.href = "/login";
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

<style lang="scss" scoped>
th {
    text-align: start;
    text-transform: capitalize;
}

tr {
    td {
        padding: 0 0.3em;
        text-align: start;

        &:nth-child(1) {
            text-transform: capitalize;
        }
        &:nth-child(2) {
            text-transform: capitalize;
        }
        &:nth-child(4) {
            min-width: 120px;
        }
        &:nth-child(5) {
            display: flex;
            justify-content: space-between;
        }
    }
    &:hover {
        background-color: var(--first-color);
        color: var(--white);
    }
}
</style>
