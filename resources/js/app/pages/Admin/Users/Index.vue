<template>
    <v-filter :params="params" @change="searching" />

    <q-toolbar class="q-ma-sm">
        <q-toolbar-title> List of users </q-toolbar-title>

        <v-create @created="getUsers"></v-create>
    </q-toolbar>
    <div class="row q-col-gutter-md q-ma-sm">
        <div
            class="col-xs-12 col-sm-6 col-md-4"
            v-for="user in users"
            :key="user.id"
        >
            <q-card flat bordered>
                <q-card-section class="q-pb-none">
                    <div class="text-h6 text-grey-7">
                        {{ user.name }} {{ user.last_name }}
                    </div>
                    <div class="text-subtitle2 text-grey-7">
                        {{ user.email }}
                    </div>
                </q-card-section>

                <q-separator />

                <q-card-actions align="around">
                    <v-update
                        v-if="!user.disabled"
                        @updated="getUsers"
                        :item="user"
                    />
                    <v-scopes v-if="!user.disabled" :item="user" />
                    <v-revoke v-if="!user.disabled" :item="user" />
                    <v-status :item="user" @updated="getUsers" />
                </q-card-actions>
            </q-card>
        </div>
    </div>

    <div class="row justify-center q-mt-md">
        <q-pagination
            v-model="search.page"
            color="grey-8"
            :max="pages.total_pages"
            size="sm"
        />
    </div>
</template>
<script>
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VScopes from "./Scopes.vue";
import VStatus from "./Status.vue";
import VRevoke from "./Revoke.vue";
export default {
    components: {
        VCreate,
        VUpdate,
        VScopes,
        VStatus,
        VRevoke,
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
                    align: "center",
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
