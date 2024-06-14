<template>
    <v-table>
        <template v-slot:title>List of users</template>
        <template v-slot:head>
            <th>name</th>
            <th>last name</th>
            <th>email</th>
            <th>phone</th>
            <th>
                <v-register bg="btn-link"></v-register>
            </th>
        </template>
        <template v-slot:body>
            <tr v-for="(item, index) in users" :key="index">
                <td v-text="item.name"></td>
                <td v-text="item.last_name"></td>
                <td v-text="item.email"></td>
                <td v-text="item.full_phone"></td>
                <td>
                    <div>
                        <v-scopes :user="item" bg="btn-primary"></v-scopes>
                    </div>
                    <div>
                        <v-update :user="item" bg="btn-secondary"></v-update>
                    </div>
                    <div>
                        <v-status :user="item" @errors="alert"></v-status>
                    </div>
                </td>
            </tr>
        </template>
    </v-table>
    <v-message :id="message_show">
        <template v-slot:body> {{ message }} </template>
    </v-message>

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
            users: {},
            pages: {},
            search: {
                page: 1,
                per_page: 30,
            },
            message: null,
            message_show: null,
        };
    },

    mounted() {
        this.getUsers();
        this.listenEvents();
    },

    watch: {
        "search.page"(value) {
            this.getUsers();
        },
    },

    methods: {
        /**
         * Send event to show message
         * @param event
         */
        alert(event) {
            if (event.status) {
                this.message_show = Math.floor(Math.random() * 10000);
                this.message = event.data.message;
            }
        },

        /**
         * Get the all users
         */
        async getUsers() {
            try {
                const res = await this.$server.get("/api/admin/users", {
                    params: this.search,
                });

                if (res.status == 204) {
                    this.message = "Cannot find results";
                    this.message_show = Math.floor(Math.random() * 10000);
                }

                if (res.status == 200 && res.data.data.length) {
                    const values = res.data.data;
                    const meta = res.data.meta;

                    this.users = values;
                    this.pages = meta.pagination;
                    this.search.page = meta.pagination.current_page;
                }
            } catch (e) {
                if (e.response && e.response.status == 403) {
                    this.alert(e.response);
                }
            }
        },

        changeList(id) {
            this.search.page = id;
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("UpdateEmployeeEvent", (e) => {
                    console.log(e);

                    this.getUsers();
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("StoreEmployeeEvent", (e) => {
                    console.log(e);
                    this.getUsers();
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("EnableEmployeeEvent", (e) => {
                    console.log(e);

                    this.getUsers();
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("DisableEmployeeEvent", (e) => {
                    console.log(e);

                    this.getUsers();
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("StoreEmployeeRoleEvent", (e) => {
                    console.log(e);

                    this.getUsers();
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyEmployeeRoleEvent", (e) => {
                    console.log(e);

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
            div {
                padding: 0.1em;
            }
        }
    }
    &:hover {
        background-color: var(--first-color);
        color: var(--white);
    }
}
</style>
