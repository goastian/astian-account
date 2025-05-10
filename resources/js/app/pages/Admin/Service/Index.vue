<template>
    <v-admin-layout>
        <v-filter :params="params" @change="searching"></v-filter>

        <q-toolbar class="q-ma-sm">
            <q-toolbar-title> List of Services </q-toolbar-title>

            <v-create @created="getServices"></v-create>
        </q-toolbar>

        <div class="row q-col-gutter-md q-pa-sm">
            <div
                v-for="service in services"
                :key="service.id"
                class="col-xs-12 col-sm-6 col-md-4 col-lg-3"
            >
                <q-card
                    flat
                    bordered
                    class="bg-white q-hoverable q-pa-sm q-mb-sm"
                    :elevation="2"
                >
                    <q-card-section class="q-pb-none">
                        <q-toolbar class="q-pa-none">
                            <q-toolbar-title class="text-ucfirst text-primary">
                                {{ service.name }}
                            </q-toolbar-title>

                            <v-detail :service="service"></v-detail>
                        </q-toolbar>
                    </q-card-section>

                    <q-separator spaced />

                    <q-card-section class="q-pt-sm q-pb-sm">
                        <div class="text-body2 text-grey-8">
                            {{ service.description }}
                        </div>
                        <div class="text-caption q-mt-sm">
                            <strong>Group:</strong> {{ service.group.name }}
                        </div>
                        <div class="text-caption">
                            <strong>System:</strong>
                            <q-badge
                                :color="service.system ? 'green' : 'orange'"
                                outline
                                class="q-ml-xs"
                            >
                                {{ service.system ? "Yes" : "No" }}
                            </q-badge>
                        </div>
                    </q-card-section>

                    <q-card-actions align="right">
                        <v-update
                            :item="service"
                            @updated="getServices"
                        ></v-update>
                        <v-delete
                            v-if="!service.system"
                            :item="service"
                            @deleted="getServices"
                        ></v-delete>
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
            services: [],
            params: [
                { key: "Name", value: "name" },
                { key: "Group", value: "group" },
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
        const values = this.$page.props.services;
        this.services = values.data;
        this.pages = values.meta.pagination;
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
            var params = {};
            Object.assign(params, this.search);
            Object.assign(params, param);

            this.$server
                .get(this.$page.props.route.services, {
                    params: params,
                })
                .then((res) => {
                    this.services = res.data.data;
                    var meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch((e) => {});
        },
    },
};
</script>
