<template>
    <v-admin-layout>
        <v-filter :params="params" @change="searching" />

        <q-toolbar class="q-ma-sm justify-between items-center">
            <q-toolbar-title>List of groups</q-toolbar-title>

            <div class="flex items-center">
                <v-create @created="getGroups" class="q-mr-md" />

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

        <div v-if="viewMode === 'grid'" class="row q-col-gutter-md q-ma-sm">
            <div
                class="col-xs-12 col-sm-6 col-md-4"
                v-for="group in groups"
                :key="group.id"
            >
                <q-card flat bordered>
                    <q-card-section class="q-pb-none">
                        <div class="text-h6 text-ucfirst">{{ group.name }}</div>
                        <div class="text-subtitle2 text-grey-7">
                            {{ group.slug }}
                        </div>
                    </q-card-section>

                    <q-card-section>
                        <div class="line-clamp-2">{{ group.description }}</div>
                    </q-card-section>

                    <q-card-section class="text-caption text-grey-8">
                        System:
                        <strong>{{ group.system ? "Yes" : "No" }}</strong>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions align="right">
                        <v-update @updated="getGroups" :item="group" />
                        <v-delete
                            v-if="!group.system"
                            @deleted="getGroups"
                            :item="group"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </div>

        <div v-else class="q-ma-sm">
            <q-list bordered separator>
                <q-item v-for="group in groups" :key="group.id" class="q-py-md">
                    <q-item-section>
                        <q-item-label class="text-h6 text-ucfirst">{{
                            group.name
                        }}</q-item-label>
                        <q-item-label caption>{{ group.slug }}</q-item-label>
                        <q-item-label>{{ group.description }}</q-item-label>
                        <q-item-label caption class="text-grey-8">
                            System:
                            <strong>{{ group.system ? "Yes" : "No" }}</strong>
                        </q-item-label>
                    </q-item-section>

                    <q-item-section side class="q-gutter-sm">
                        <v-update @updated="getGroups" :item="group" />
                        <v-delete
                            v-if="!group.system"
                            @deleted="getGroups"
                            :item="group"
                        />
                    </q-item-section>
                </q-item>
            </q-list>
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

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
    },

    data() {
        return {
            groups: [],
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
                { name: "name", label: "Name", field: "name", align: "left" },
                { name: "slug", label: "Slug", field: "slug", align: "left" },
                {
                    name: "description",
                    label: "Description",
                    field: "description",
                    align: "left",
                },
                {
                    name: "system",
                    label: "System",
                    field: (row) => (row.system ? "Yes" : "No"),
                    align: "center",
                },
                {
                    name: "actions",
                    label: "Actions",
                    align: "right",
                },
            ],
        };
    },

    created() {
        const values = this.$page.props.groups;
        this.groups = values.data;
        this.pages = values.meta.pagination;
    },

    watch: {
        "search.page"(value) {
            this.getGroups();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getGroups();
            }
        },
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getGroups(event);
        },

        getGroups(param = null) {
            var params = {};
            Object.assign(params, this.search);
            Object.assign(params, param);

            this.$server
                .get(this.$page.props.route, {
                    params: params,
                })
                .then((res) => {
                    this.groups = res.data.data;
                    var meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch((e) => {});
        },
    },
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
