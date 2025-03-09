<template>
    <q-page>
        <v-filter :params="params" @change="searching"></v-filter>
        <q-table
            flat
            bordered
            :rows="users"
            :columns="headers"
            row-key="name"
            hide-bottom
            :rows-per-page-options="[search.per_page]"
            hide-pagination
        >
            <template v-slot:top>
                <h5>List of users</h5>
                <q-space />
                <v-create @created="getUsers"></v-create>
            </template>
            <template v-slot:body-cell-actions="props">
                <q-td>
                    <v-update @updated="getUsers" :item="props.row"></v-update>
                    <v-scopes :item="props.row"></v-scopes>
                </q-td>
            </template>
        </q-table>

        <div class="row justify-center q-mt-md">
            <q-pagination
                v-model="search.page"
                color="grey-8"
                :max="pages.total_pages"
                size="sm"
            />
        </div>
    </q-page>
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
                { label: "Name", name: "name", field: "name", align: "left" },
                {
                    label: "Last Name",
                    name: "last_name",
                    field: "last_name",
                    align: "left",
                },
                {
                    label: "Email",
                    name: "email",
                    field: "email",
                    align: "left",
                },
                {
                    label: "Actions",
                    name: "actions",
                    field: "actions",
                    align: "left",
                },
            ],
            params: [
                { key: "Name", value: "name" },
                { key: "Last name", value: "last_name" },
                { key: "Email", value: "email" },
                { key: "Created", value: "created" },
                { key: "Updated", value: "updated" },
            ],
            users: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 0,
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
