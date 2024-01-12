<template>
    <v-create @scope-was-created="getScopes(actual_page)"></v-create>
    <v-table
        :items="items"
        class="text-sm table-sm text-center"
        style="width: 70%; margin: 1% auto"
    >
        <template v-slot:body>
            <tr v-for="(item, index) in scopes" :key="index">
                <td>{{ item.role }}</td>
                <td>{{ item.descripcion }}</td>
                <td>
                    <v-update
                        :role="item"
                        @scope-was-updated="getScopes(actual_page)"
                    ></v-update>
                    <v-remove
                        :role="item"
                        @scope-was-remove="getScopes(actual_page)"
                    ></v-remove>
                </td>
            </tr>
        </template>
    </v-table>
    <v-pagination :pages="pages" @send-current-page="changePage"></v-pagination>
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
            items: ["id", "descripcion"],
            scopes: {},
            pages: {},
            actual_page: 1,
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
                    console.log(e.response);
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
