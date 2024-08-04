<template>
    <div class="users">
        <div class="head">
            <div>
                <p>
                    List of users

                    <el-input-number
                        v-model="search.per_page"
                        :min="1"
                        :max="10"
                    />
                </p>
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
            <el-table-column label="Operations" min-width="300">
                <template #default="scope">
                    <div class="actions">
                        <v-scopes :user="scope.row"></v-scopes>

                        <v-update :user="scope.row"></v-update>

                        <v-status :user="scope.row"></v-status>
                    </div>
                </template>
            </el-table-column>
        </el-table>

        <el-pagination
            v-if="pages.total > 0"
            background
            layout="prev, pager, next"
            @change="changePage"
            :page-count="pages.total_pages"
            :total="pages.total"
        />
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
                per_page: 50,
            },
        };
    },

    created() {
        this.getUsers();
        this.listenEvents();
    },

    watch: {
        "search.page"(value) {
            this.getUsers();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getUsers();
            }
        },
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },
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
                    this.search.current_page = meta.pagination.current_page;
                }
            } catch (e) {}
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
        flex-wrap: wrap;

        .box {
            flex: 1 1 100%;
            margin: 0.2em;
        }
    }
}
</style>
