<template>
    <v-admin-layout>
        <!-- Filtro -->
        <v-filter :params="params" @change="searching" />

        <!-- Barra superior -->
        <q-toolbar class="q-ma-sm q-gutter-sm items-center justify-between">
            <q-toolbar-title class="text-h6 text-primary"
                >List of Services</q-toolbar-title
            >

            <div class="row items-center q-gutter-sm">
                <v-create @created="getServices" />

                <!-- Toggle vista -->
                <q-btn-toggle
                    v-model="viewMode"
                    dense
                    unelevated
                    toggle-color="primary"
                    color="primary"
                    text-color="white"
                    :options="[
                        { value: 'list', icon: 'list' },
                        { value: 'grid', icon: 'grid_on' },
                    ]"
                />
            </div>
        </q-toolbar>

        <!-- VISTA EN GRID -->
        <div v-if="viewMode === 'grid'" class="row q-col-gutter-md q-pa-sm">
            <div
                v-for="service in services"
                :key="service.id"
                class="col-xs-12 col-sm-6 col-md-4 col-lg-3"
            >
                <q-card bordered flat class="q-hoverable" :elevation="1">
                    <q-card-section class="q-pa-sm bg-grey-1">
                        <div class="row justify-between items-start">
                            <div>
                                <div class="text-subtitle1 text-primary">
                                    {{ service.name }}
                                </div>
                                <div class="text-caption text-grey-7">
                                    {{ service.group.name }}
                                </div>
                            </div>
                            <v-detail :service="service" />
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-section class="q-pt-sm q-pb-sm">
                        <div class="text-body2 text-grey-8 line-clamp-2">
                            {{ service.description }}
                        </div>

                        <div class="q-mt-sm text-caption">
                            <strong>System:</strong>
                            <q-badge
                                :color="service.system ? 'green' : 'orange'"
                                outline
                                class="q-ml-xs"
                            >
                                {{ service.system ? "Yes" : "No" }}
                            </q-badge>
                        </div>

                        <div class="text-caption">
                            <strong>Visibility:</strong>
                            <q-badge outline class="q-ml-xs" color="blue">
                                {{ service.visibility }}
                            </q-badge>
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions align="right">
                        <v-update :item="service" @updated="getServices" />
                        <v-delete
                            v-if="!service.system"
                            :item="service"
                            @deleted="getServices"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </div>

        <!-- VISTA EN LISTA -->
        <q-list v-else bordered separator class="q-ma-sm">
            <q-item
                v-for="service in services"
                :key="service.id"
                class="q-pa-sm"
                clickable
            >
                <q-item-section>
                    <q-item-label class="text-subtitle1 text-primary">
                        {{ service.name }}
                    </q-item-label>
                    <q-item-label caption class="text-grey-7">
                        {{ service.description }}
                    </q-item-label>

                    <div class="row items-center q-mt-xs text-caption">
                        <div class="q-mr-sm">
                            <strong>Group:</strong> {{ service.group.name }}
                        </div>
                        <div class="q-mr-sm">
                            <strong>System:</strong>
                            <q-badge
                                :color="service.system ? 'green' : 'orange'"
                                outline
                                class="q-ml-xs"
                            >
                                {{ service.system ? "Yes" : "No" }}
                            </q-badge>
                        </div>
                        <div>
                            <strong>Visibility:</strong>
                            <q-badge outline color="blue" class="q-ml-xs">
                                {{ service.visibility }}
                            </q-badge>
                        </div>
                    </div>
                </q-item-section>

                <q-item-section side top>
                    <div class="row no-wrap items-center q-gutter-xs">
                        <v-detail :service="service" />
                        <v-update :item="service" @updated="getServices" />
                        <v-delete
                            v-if="!service.system"
                            :item="service"
                            @deleted="getServices"
                        />
                    </div>
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
import VDetail from "./Scope.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
        VDetail,
    },

    data() {
        return {
            viewMode: "list",
            services: [],
            params: [
                { key: "Name", value: "name" },
                { key: "Group", value: "group" },
                { key: "Visibility", value: "visibility" },
                { key: "Created", value: "created" },
                { key: "Updated", value: "updated" },
            ],
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
        this.getServices();
    },

    watch: {
        "search.page"(value) {
            this.getServices();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getServices();
            }
        },
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getServices(event);
        },

        getServices(param = null) {
            var params = { ...this.search, ...param };

            this.$server
                .get(this.$page.props.route.services, {
                    params: params,
                })
                .then((res) => {
                    this.services = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch((e) => {});
        },
    },
};
</script>
