<template>
    <v-create @success="getScopes(actual_page)"></v-create>
    <v-table :items="items">
        <template v-slot:body>
            <tr v-for="(item, index) in scopes" :key="index">
                <td>{{ item.role }}</td>
                <td>{{ item.descripcion }}</td>
                <td>
                    <v-update
                        :role="item"
                        @success="getScopes(actual_page)"
                    ></v-update>
                </td>
                <td>
                    <v-remove
                        :role="item"
                        @success="getScopes(actual_page)"
                        @errors="showMessage"
                    ></v-remove>
                </td>
            </tr>
        </template>
    </v-table>
    <v-pagination v-show="pages.total > pages.per_page" :pages="pages" @send-current-page="changePage"></v-pagination>

    <v-message :message="message" @close="close"></v-message>
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
            items: ["id", "Description"],
            scopes: {},
            pages: {},
            actual_page: 1,
            message: null,
        };
    },

    beforeMount() {
        this.actual_page = 1;
    },

    mounted() {
        this.getScopes(this.actual_page);
        this.listenEvent();
    },

    methods: {
        showMessage(event) {
            this.message = event.data.message;
        },

        close() {
            this.message = null;
        },

        changePage(page) {
            this.actual_page = page;
            this.getScopes(page);
        },

        getScopes(id) {
            this.$server
                .get("/api/roles", {
                    params: {
                        page: id,
                    },
                })
                .then((res) => {
                    this.scopes = res.data.data;
                    this.pages = res.data.meta.pagination;
                    this.actual_page = res.data.meta.pagination.current_page;
                })
                .catch((e) => {
                    if (e.response && e.response.status == 403) {
                        this.message = e.response.data.message;
                    }
                });
        },

        listenEvent() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("StoreRoleEvent", (e) => {
                    this.getScopes(this.actual_page);
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("UpdateRoleEvent", (e) => {
                    this.getScopes(this.actual_page);
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyRoleEvent", (e) => {
                    this.getScopes(this.actual_page);
                });
        },
    },
};
</script>