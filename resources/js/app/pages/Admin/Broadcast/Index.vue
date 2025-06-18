<template>
    <v-admin-layout>
        <v-filter :params="params" @change="searching" />
        <q-toolbar class="q-ma-sm">
            <q-toolbar-title> List of channels </q-toolbar-title>
            <div class="row items-center q-pa-md">
                <v-create @created="getBroadcasting"></v-create>
                <q-space />
                <q-btn-toggle v-model="viewMode" dense toggle-color="primary" :options="[
                    { value: 'list', icon: 'list' },
                    { value: 'grid', icon: 'grid_on' },
                ]" unelevated />
            </div>
        </q-toolbar>

        <div v-if="viewMode === 'grid'" class="row q-col-gutter-md q-ma-sm">
            <div class="col-xs-12 col-sm-6 col-md-4" v-for="channel in channels" :key="channel.id">
                <q-card bordered flat>
                    <q-card-section class="q-pb-none">
                        <div class="text-h6">
                            {{ channel.name }}
                        </div>
                        <div class="text-subtitle2 text-grey">
                            {{ channel.slug }}
                        </div>
                    </q-card-section>

                    <q-card-section>
                        <div class="q-mb-sm">
                            <strong>Description:</strong>
                            {{ channel.description || "—" }}
                        </div>
                        <div>
                            <strong>System:</strong>
                            <q-chip :color="channel.system ? 'green' : 'red'" text-color="white" dense>
                                {{ channel.system ? "Yes" : "No" }}
                            </q-chip>
                        </div>
                    </q-card-section>

                    <q-card-actions align="right">
                        <v-destroy v-if="!channel.system" :item="channel" @deleted="getBroadcasting" />
                    </q-card-actions>
                </q-card>
            </div>
        </div>

        <div v-if="viewMode === 'list'" class="q-ma-sm">
            <q-card flat bordered>
                <q-card-section>
                    <div class="text-h6">Channels Table</div>
                </q-card-section>
                <q-table :rows="channels" :columns="columns" row-key="id" flat bordered hide-bottom
                    :rows-per-page-options="[search.per_page]">
                    <template v-slot:body-cell-system="props">
                        <q-td :props="props">
                            <q-chip :color="props.value ? 'green' : 'red'" text-color="white" dense>
                                {{ props.value ? "Yes" : "No" }}
                            </q-chip>
                        </q-td>
                    </template>

                    <template v-slot:body-cell-actions="props">
                        <q-td :props="props">
                            <v-destroy v-if="!props.row.system" :item="props.row" @deleted="getBroadcasting" />
                        </q-td>
                    </template>
                </q-table>
            </q-card>
        </div>

        <div class="row justify-center q-mt-md">
            <q-pagination v-model="search.page" color="grey-8" :max="pages.total_pages" size="sm" />
        </div>
    </v-admin-layout>
</template>
<script>
import VCreate from "./Create.vue";
import VDestroy from "./Destroy.vue";
export default {
    components: {
        VCreate,
        VDestroy,
    },

    data() {
        return {
            params: [
                { key: "Name", value: "name" },
                { key: "Description", value: "description" },
                { key: "slug", value: "slug" },
                { key: "System", value: "system" },
            ],
            viewMode: "list",
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
                    field: "system",
                    sortable: true,
                },
                { name: "actions", label: "Actions", field: "actions" },
            ],

            channels: [],
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
        this.getBroadcasting();
    },

    watch: {
        "search.page"(value) {
            this.getBroadcasting();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getBroadcasting();
            }
        },
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getBroadcasting(event);
        },
        /**
         * Get the all channels
         */
        async getBroadcasting(param = null) {
            //setting searching params
            var params = { ...this.search, ...param };

            try {
                const res = await this.$server.get("/admin/broadcasts", {
                    params: params,
                });

                if (res.status == 200 && res.data.data.length) {
                    const values = res.data.data;
                    const meta = res.data.meta;

                    this.channels = values;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                }
            } catch (e) { }
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("CreatedBroadcasting", (e) => {
                    this.getBroadcasting();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyedBroadcasting", (e) => {
                    this.getBroadcasting();
                });
        },
    },
};
</script>
