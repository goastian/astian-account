<template>
    <v-sheet class="px-3">
        <v-data-table
            :items-per-page="search.per_page"
            :headers="headers"
            :items="users"
        >
            <template v-slot:top>
                <div class="flex mx-3 justify-between align-center">
                    <h1 class="fw-bold">List of users</h1>
                    <v-create @created="getUsers"></v-create>
                </div>
                <v-filter :params="params" @change="searching"></v-filter>
            </template>
            <template #item.actions="{ item }">
                <v-update :item="item" @updated="getUsers"></v-update>
                <v-scopes :item="item"></v-scopes>
            </template>
            <template v-slot:bottom>
                <v-pagination
                    v-model="search.page"
                    :length="pages.total_pages"
                    :total-visible="7"
                ></v-pagination>
            </template>
        </v-data-table>
    </v-sheet>
</template>
<script>
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VScopes from "./Scopes.vue";
export default {
    components: {
        VCreate,
        VUpdate,
        VScopes,
    },

    data() {
        return {
            headers: [
                { title: "Name", value: "name", align: "start" },
                { title: "Last Name", value: "last_name", align: "start" },
                { title: "Email", value: "email", align: "start" },
                { title: "Actions", value: "actions", align: "center" },
            ],
            params: [
                { key: "Name", value: "name" },
                { key: "Last name", value: "last_name" },
                { key: "Email", value: "email" },
                { key: "Created", value: "created" },
                { key: "Updated", value: "updated" },
            ],
            users: [],
            pages: {},
            search: {
                page: 1,
                per_page: 15,
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

        searching(event) {
            this.getUsers(event);
        },
        /**
         * Get the all users
         */
        async getUsers(param = null) {
            //setting searching params
            var params = {};
            Object.assign(params, this.search);
            Object.assign(params, param);

            try {
                const res = await this.$server.get("/api/admin/users", {
                    params: params,
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
