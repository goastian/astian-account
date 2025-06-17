<template>
    <v-admin-layout>
        <v-filter :params="params" @change="searching" />

        <q-toolbar class="q-ma-sm flex items-center justify-between">
            <q-toolbar-title>List of roles</q-toolbar-title>

            <div class="row items-center q-pa-md">
                <!-- Create Button -->
                <div class="q-mr-sm">
                    <v-create @created="getRoles" />
                </div>

                <!-- Toggle View Mode -->
                <q-btn-toggle v-model="viewMode" dense toggle-color="primary" :options="[
                    { value: 'list', icon: 'list' },
                    { value: 'grid', icon: 'grid_on' },
                ]" unelevated />
            </div>
        </q-toolbar>

        <!-- Grid view -->
        <div class="row q-col-gutter-md q-ma-sm" v-if="viewMode === 'grid'">
            <div class="col-xs-12 col-sm-6 col-md-4" v-for="role in roles" :key="role.id">
                <q-card flat bordered>
                    <q-card-section class="q-pb-none">
                        <div class="text-h6 text-ucfirst">{{ role.name }}</div>
                        <div class="text-subtitle2 text-grey-7">
                            {{ role.slug }}
                        </div>
                    </q-card-section>

                    <q-card-section>
                        <div class="text-body2 line-clamp-2">
                            {{ role.description }}
                        </div>
                    </q-card-section>

                    <q-card-section class="text-caption text-grey-8 q-pt-none">
                        System:
                        <strong>{{ role.system ? "Yes" : "No" }}</strong>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions align="right">
                        <v-update @updated="getRoles" :item="role" />
                        <v-delete v-if="!role.system" @deleted="getRoles" :item="role" />
                    </q-card-actions>
                </q-card>
            </div>
        </div>

        <!-- List view -->
        <!-- List view con q-list -->
        <div v-else class="q-ma-sm">
            <q-list bordered separator>
                <q-item v-for="role in roles" :key="role.id" clickable class="q-pa-sm">
                    <q-item-section>
                        <q-item-label class="text-h6">{{
                            role.name
                        }}</q-item-label>
                        <q-item-label caption>{{ role.slug }}</q-item-label>
                        <q-item-label>{{ role.description }}</q-item-label>
                        <q-item-label caption>
                            System:
                            <strong>{{ role.system ? "Yes" : "No" }}</strong>
                        </q-item-label>
                    </q-item-section>

                    <q-item-section side>
                        <v-update @updated="getRoles" :item="role" />
                        <v-delete v-if="!role.system" @deleted="getRoles" :item="role" />
                    </q-item-section>
                </q-item>
            </q-list>
        </div>

        <div class="row justify-center q-mt-md">
            <q-pagination v-model="search.page" color="grey-8" :max="pages.total_pages" size="sm" />
        </div>
    </v-admin-layout>
</template>

<script>
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
    },

    data() {
        return {
            roles: [],
            viewMode: "list",
            params: [{ key: "Name", value: "name" }],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            columns: [
                { name: "name", label: "Name", field: "name", sortable: true },
                { name: "slug", label: "Slug", field: "slug", sortable: true },
                {
                    name: "description",
                    label: "Description",
                    field: "description",
                    sortable: false,
                },
                {
                    name: "system",
                    label: "System",
                    field: (row) => (row.system ? "Yes" : "No"),
                    sortable: true,
                },
                { name: "actions", label: "Actions", field: "actions" },
            ],
        };
    },

    created() {
        this.getRoles()
    },

    watch: {
        "search.page"(value) {
            this.getRoles();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getRoles();
            }
        },
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getRoles(event);
        },

        getRoles(param = null) {
            var params = { ...this.search, ...params };

            this.$server
                .get(this.$page.props.route, {
                    params: params,
                })
                .then((res) => {
                    this.roles = res.data.data;
                    var meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch((e) => { });
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$q.notify({
                    type: "positive",
                    message: "Copy successfully",
                    timeout: 3000,
                });
            } catch (err) { }
        },
    },
};
</script>
