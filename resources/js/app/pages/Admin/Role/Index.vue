<template>
    <v-admin-layout>
        <!-- Filtro superior -->
        <v-filter :params="params" @change="searching" />

        <!-- Encabezado con acciones -->
        <q-toolbar class="q-pa-md q-gutter-sm justify-between">
            <q-toolbar-title class="text-h5 text-primary">
                List of Roles
            </q-toolbar-title>

            <div class="row items-center q-gutter-sm">
                <!-- Botón de crear -->
                <v-create @created="getRoles" />

                <!-- Cambio de vista -->
                <q-btn-toggle
                    v-model="viewMode"
                    dense
                    toggle-color="primary"
                    :options="[
                        { value: 'list', icon: 'list', label: 'List' },
                        { value: 'grid', icon: 'grid_on', label: 'Grid' },
                    ]"
                    unelevated
                />
            </div>
        </q-toolbar>

        <!-- Vista en grid -->
        <div v-if="viewMode === 'grid'" class="q-ma-sm row q-col-gutter-md">
            <div
                v-for="role in roles"
                :key="role.id"
                class="col-xs-12 col-sm-6 col-md-4"
            >
                <q-card flat bordered class="shadow-1">
                    <q-card-section class="q-pb-xs">
                        <div class="text-h6">{{ role.name }}</div>
                        <div class="text-subtitle2 text-grey-6">
                            {{ role.slug }}
                        </div>
                    </q-card-section>

                    <q-card-section class="q-pt-none">
                        <div class="text-body2 ellipsis-2-lines">
                            {{ role.description }}
                        </div>
                    </q-card-section>

                    <q-card-section class="q-pt-none text-caption text-grey-7">
                        System:
                        <strong>{{ role.system ? "Yes" : "No" }}</strong>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions align="right" class="q-pa-sm">
                        <v-update @updated="getRoles" :item="role" />
                        <v-delete
                            v-if="!role.system"
                            @deleted="getRoles"
                            :item="role"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </div>

        <!-- Vista en lista -->
        <q-list v-else bordered separator class="q-ma-sm">
            <q-item
                v-for="role in roles"
                :key="role.id"
                class="q-pa-md q-hoverable"
            >
                <q-item-section>
                    <q-item-label class="text-h6">{{ role.name }}</q-item-label>
                    <q-item-label caption>{{ role.slug }}</q-item-label>
                    <q-item-label class="q-mt-xs text-body2">
                        {{ role.description }}
                    </q-item-label>
                    <q-item-label caption class="q-mt-xs">
                        System:
                        <strong>{{ role.system ? "Yes" : "No" }}</strong>
                    </q-item-label>
                </q-item-section>

                <q-item-section side class="q-gutter-sm">
                    <v-update @updated="getRoles" :item="role" />
                    <v-delete
                        v-if="!role.system"
                        @deleted="getRoles"
                        :item="role"
                    />
                </q-item-section>
            </q-item>
        </q-list>

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
        this.getRoles();
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
            var params = { ...this.search, ...param };

            this.$server
                .get(this.$page.props.route, {
                    params: params,
                })
                .then((res) => {
                    this.roles = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch((e) => {});
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$q.notify({
                    type: "positive",
                    message: "Copy successfully",
                    timeout: 3000,
                });
            } catch (err) {}
        },
    },
};
</script>

<style lang="css" scoped>
.ellipsis-2-lines {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
