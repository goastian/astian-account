<template>
    <div class="users">
        <div class="head">
            <div>
                <p>List of users</p>
            </div>
            <div>
                <v-register></v-register>
            </div>
        </div>
        <el-table :data="users" :lazy="true">
            <el-table-column prop="name" label="Name" width="200" />
            <el-table-column prop="last_name" label="Last Name" width="200" />
            <el-table-column prop="email" label="Email" width="200" />
            <el-table-column prop="full_phone" label="Phone" width="200" />
            <el-table-column label="Operations" min-width="200">
                <template #default="scope">
                    <div class="actions">
                        <div class="box">
                            <v-scopes :user="scope.row"></v-scopes>
                        </div>
                        <div class="box">
                            <v-update :user="scope.row"></v-update>
                        </div>
                        <div class="box">
                            <v-status :user="scope.row"></v-status>
                        </div>
                    </div>
                </template>
            </el-table-column>
        </el-table>

        <v-pagination
            v-show="pages.total > pages.per_page"
            :pages="pages"
            @send-current-page="changeList"
        ></v-pagination>
    </div>
</template>
<script>
import VRegister from "./Register.vue";
import VUpdate from "./Update.vue";
import VStatus from "./Status.vue";
import VScopes from "./Scopes.vue";

export default {
    components: {
        VRegister,
        VUpdate,
        VStatus,
        VScopes,
    },

    data() {
        return {
            users: [],
            pages: {},
            search: {
                page: 1,
                per_page: 30,
            },
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
         * Get the all users
         */
        async getUsers() {
            try {
                const res = await this.$server.get("/api/admin/users", {
                    params: this.search,
                });

                if (res.status == 200 && res.data.data.length) {
                    const values = res.data.data;
                    const meta = res.data.meta;

                    this.users = values;
                    this.pages = meta.pagination;
                    this.search.page = meta.pagination.current_page;
                }
            } catch (e) {}
        },

        changeList(id) {
            this.search.page = id;
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
.users {
    .head {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        div {
            flex: 1 1 calc(100% / 2);

            p {
                margin: 0;
                font-size: 1.2em;
            }

            &:nth-child(2) {
                text-align: right;
            }
        }
    }
    .actions {
        display: flex;
        .box {
            flex: auto;
        }
    }
}
</style>
