<template>
    <v-create @success="getScopes()"></v-create>
    <v-table>
        <template v-slot:title> List of Scopes </template>
        <template v-slot:head>
            <th>scope</th>
            <th>description</th>
            <th>public</th>
            <th>require payment</th>
        </template>
        <template v-slot:body>
            <tr v-for="(item, index) in scopes" :key="index">
                <td>{{ item.scope }}</td>
                <td>{{ item.description }}</td>
                <td>{{ item.public ? "Yes" : "No" }}</td>
                <td>
                    {{ item.required_payment ? "Yes" : "No" }}
                </td>
                <td>
                    <div>
                        <v-update
                            :scope="item"
                            @success="getScopes()"
                        ></v-update>
                    </div>
                    <div>
                        <v-remove
                            :scope="item"
                            @success="getScopes()"
                            @errors="showMessage"
                        ></v-remove>
                    </div>
                </td>
            </tr>
        </template>
    </v-table>
    <v-pagination
        v-show="pages.total > pages.per_page"
        :pages="pages"
        @send-current-page="changePage"
    ></v-pagination>

    <v-message :id="message_show">
        <template v-slot:body>
            {{ message }}
        </template>
    </v-message>
</template>
<script>
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VRemove from "./Remove.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VRemove,
    },

    data() {
        return {
            scopes: {},
            pages: {},
            search: {
                page: 1,
                per_page: 2,
            },
            message: null,
            message_show: null,
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
        showMessage(event) {
            this.message_show = Math.floor(Math.random() * 10000);
            this.message = event.data.message;
        },

        changePage(page) {
            this.search.page = page;
        },

        async getScopes() {
            try {
                const res = await this.$server.get("/api/admin/roles", {
                    params: this.search,
                });

                if (res.status == 204) {
                    this.message = "Cannot find results";
                    this.message_show = Math.floor(Math.random() * 10000);
                }

                if (res.status == 200) {
                    const values = res.data.data;
                    const meta = res.data.meta;

                    this.scopes = values;
                    this.pages = meta.pagination;
                    this.actual_page = meta.pagination.current_page;
                }
            } catch (e) {
                if (e.response && e.response.status == 403) {
                    this.message = e.response.data.message;
                }
                if (e.response && e.response.status == 401) {
                    window.location.href = "/login";
                }
            }
        },

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
th {
    text-align: start;
    text-transform: capitalize;
}

tr {
    td {
        min-width: 100px;
        &:nth-child(2) {
            min-width: 200px;
        }

        &:nth-child(5) {
            display: flex;
            justify-content: space-around;
            div {
                padding: 0.1em;
            }
        }
    }
}
</style>
