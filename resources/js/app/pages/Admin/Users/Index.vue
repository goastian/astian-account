<template>
    <v-admin-layout>
        <!-- Filters and Create Button -->
        <v-filter :params="params" @change="searching" />

        <q-toolbar class="q-ma-sm flex items-center justify-between">
            <q-toolbar-title>List of Users</q-toolbar-title>

            <div class="row items-center q-pa-md">
                <!-- Create Button -->
                <div class="q-mr-sm">
                    <v-create @created="getUsers" />
                </div>

                <!-- Toggle View Mode -->
                <q-btn-toggle
                    v-model="viewMode"
                    dense
                    toggle-color="primary"
                    :options="[
                        { value: 'list', icon: 'list' },
                        { value: 'grid', icon: 'grid_on' },
                    ]"
                    unelevated
                />
            </div>
        </q-toolbar>

        <!-- Grid View (Cards) -->
        <div v-if="viewMode === 'grid'" class="row q-col-gutter-md q-ma-sm">
            <div
                class="col-xs-12 col-sm-6 col-md-4"
                v-for="user in users"
                :key="user.id"
            >
                <q-card flat bordered class="shadow-2">
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

        <!-- List View (Table) -->
        <div v-if="viewMode === 'list'" class="q-pa-md">
            <q-table
                :rows="users"
                :columns="columns"
                row-key="id"
                class="shadow-1"
                flat
                bordered
                hide-bottom
                :rows-per-page-options="[search.per_page]"
            >
                <template v-slot:body-cell-actions="props">
                    <q-td align="right">
                        <v-update
                            ref="update_{{ props.row.id }}"
                            :item="props.row"
                            @updated="getUsers"
                        />
                        <v-scopes
                            ref="scopes_{{ props.row.id }}"
                            :item="props.row"
                        />
                        <v-revoke
                            ref="revoke_{{ props.row.id }}"
                            :item="props.row"
                        />
                        <v-status
                            ref="status_{{ props.row.id }}"
                            :item="props.row"
                            @updated="getUsers"
                        />
                    </q-td>
                </template>
            </q-table>
        </div>

        <div class="row justify-center q-my-md">
            <q-pagination
                v-model="search.page"
                color="primary"
                :max="pages.total_pages"
                size="sm"
                boundary-numbers
                direction-links
                class="q-pa-xs q-gutter-sm rounded-borders"
            />
        </div>
    </v-admin-layout>
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
            viewMode: "list",
            columns: [
                {
                    name: "name",
                    label: "Name",
                    field: (row) => `${row.name} ${row.last_name}`,
                    sortable: true,
                    align: "left",
                },
                {
                    name: "email",
                    label: "Email",
                    field: "email",
                    sortable: true,
                    align: "left",
                },
                {
                    name: "actions",
                    label: "Actions",
                    field: "actions",
                    sortable: false,
                    align: "center",
                },
            ],
            params: [
                { key: "Name", value: "name" },
                { key: "Last Name", value: "last_name" },
                { key: "Email", value: "email" },
                { key: "Created", value: "created" },
                { key: "Updated", value: "updated" },
            ],
            users: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
        };
    },

    created() {
        this.getUsers();
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
            // Setting search params
            var params = { ...this.search, ...param };

            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: params,
                });

                if (res.status == 200 && res.data.data.length) {
                    this.users = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                }
            } catch (e) {}
        },

        /**
        listenEvents() {
            const events = [
                "UserCreated",
                "UserUpdated",
                "UserEnabled",
                "UserDisabled",
                "UserDestroyed",
                "UserPasswordUpdated",
                "UserRegistered",
            ];

            events.map((item) => {
                this.$echo.private("auth").toOthers(item, (e) => {
                    this.getUsers();
                });
            });
        },*/
    },
};
</script>
