<template>
    <div class="scopes">
        <div class="head">
            <div class="row">
                <div class="col">
                    <p>List of roles</p>
                </div>
                <div class="col"><v-create></v-create></div>
            </div>
        </div>

        <div class="table" style="margin-bottom: 1em">
            <el-table :data="scopes" :lazy="true">
                <el-table-column prop="scope" label="Scope" width="200" />
                <el-table-column
                    prop="description"
                    label="description"
                    width="300"
                />
                <el-table-column prop="public" label="public" width="100">
                    <template #default="scope">
                        {{ scope.row.public ? "Yes" : "No" }}
                    </template>
                </el-table-column>
                <el-table-column
                    prop="required_payment"
                    label="require payment"
                    width="200"
                >
                    <template #default="scope">
                        {{ scope.row.required_payment ? "Yes" : "No" }}
                    </template>
                </el-table-column>
                <el-table-column label="Operations" min-width="200">
                    <template #default="scope">
                        <div class="actions">
                            <div class="box">
                                <v-update :scope="scope.row"></v-update>
                            </div>

                            <div class="box">
                                <v-remove :scope="scope.row"></v-remove>
                            </div>
                        </div>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </div>
</template>
<script>
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VRemove from "./Remove.vue";
import { ElMessage } from "element-plus";

export default {
    components: {
        VCreate,
        VUpdate,
        VRemove,
    },

    data() {
        return {
            scopes: [],
            pages: {},
            search: {
                page: 1,
                per_page: 50,
            },
        };
    },

    mounted() {
        this.getScopes();
        this.listenEvent();
    },

    watch: {
        "search.page"(value) {
            this.getScopes();
        },
    },

    methods: {
        /**
         * change page on pagination
         */
        changePage(page) {
            this.search.page = page;
        },

        /**
         * message
         */
        popup(message, type = "success") {
            if (message) {
                ElMessage({
                    message: message,
                    type: type,
                });
            }
        },

        /**
         * Get the all scopes
         */
        async getScopes() {
            try {
                const res = await this.$server.get("/api/admin/roles", {
                    params: this.search,
                });

                if (res.status == 200) {
                    const values = res.data.data;
                    const meta = res.data.meta;

                    this.scopes = values;
                    this.pages = meta.pagination;
                    this.actual_page = meta.pagination.current_page;
                }
            } catch (e) {
                if (e.response && e.response.status == 403) {
                    this.popup(e.response.data.message, "warning");
                }
            }
        },

        /**
         * Listen events
         */
        listenEvent() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("StoreRoleEvent", (e) => {
                    this.getScopes();
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("UpdateRoleEvent", (e) => {
                    this.getScopes();
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyRoleEvent", (e) => {
                    this.getScopes();
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.scopes {
    .head {
        .row {
            display: flex;
            flex-wrap: wrap;

            .col {
                flex: 1 1 calc(100% / 2);
                p {
                    margin: 0;
                    font-size: 1.2em;
                }
                &:nth-child(2) {
                    text-align: center;
                }
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
