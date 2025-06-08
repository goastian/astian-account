<template>
    <v-admin-layout>
        <q-toolbar class="q-ma-sm flex items-center justify-between">
            <q-toolbar-title> List of clients </q-toolbar-title>
            <q-space />

            <div class="row items-center q-pa-md">
                <v-create @created="getClients()" />

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
                v-for="(client, index) in clients"
                :key="index"
            >
                <q-card class="q-pa-md">
                    <q-card-section>
                        <div class="text-h6">{{ client.name }}</div>
                        <div class="text-caption text-grey">
                            Created: {{ client.created_at }}
                        </div>
                        <div class="text-caption text-grey">
                            Updated: {{ client.updated_at }}
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-section class="q-pt-sm">
                        <div class="q-mb-sm">
                            <q-chip
                                clickable
                                @click="copyToClipboard(client.id)"
                                color="green"
                                text-color="white"
                                icon="mdi-content-copy"
                                label="ID: *****"
                            >
                                <q-tooltip>Copy ID</q-tooltip>
                            </q-chip>
                        </div>
                        <div class="q-mb-sm">
                            <q-chip
                                v-if="client.secret"
                                clickable
                                @click="copyToClipboard(client.secret)"
                                color="green"
                                text-color="white"
                                icon="mdi-content-copy"
                                label="Secret: *****"
                            >
                                <q-tooltip>Copy Secret</q-tooltip>
                            </q-chip>
                        </div>
                    </q-card-section>

                    <q-card-actions align="right">
                        <v-update :item="client" @updated="getClients" />
                        <v-delete :item="client" @deleted="getClients" />
                    </q-card-actions>
                </q-card>
            </div>
        </div>

        <div v-if="viewMode === 'list'" class="q-pa-sm">
            <q-table
                :rows="clients"
                :columns="columns"
                row-key="id"
                flat
                bordered
                hide-bottom
                :rows-per-page-options="[search.per_page]"
            >
                <template v-slot:body-cell-id="props">
                    <q-td>
                        <q-btn
                            dense
                            flat
                            icon="mdi-content-copy"
                            @click="copyToClipboard(props.row.id)"
                            label="*****"
                            size="sm"
                            color="green"
                        >
                            <q-tooltip>Copy ID</q-tooltip>
                        </q-btn>
                    </q-td>
                </template>

                <template v-slot:body-cell-secret="props">
                    <q-td>
                        <q-btn
                            v-if="props.row.secret"
                            dense
                            flat
                            icon="mdi-content-copy"
                            @click="copyToClipboard(props.row.secret)"
                            label="*****"
                            size="sm"
                            color="green"
                        >
                            <q-tooltip>Copy Secret</q-tooltip>
                        </q-btn>
                    </q-td>
                </template>

                <template v-slot:body-cell-actions="props">
                    <q-td align="right">
                        <v-update :item="props.row" @updated="getClients" />
                        <v-delete :item="props.row" @deleted="getClients" />
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
import VDelete from "./Delete.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
    },

    data() {
        return {
            viewMode: "list", // cards or table

            clients: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },

            columns: [
                { name: "name", label: "Name", field: "name", align: "left" },
                { name: "created_at", label: "Created", field: "created_at" },
                { name: "updated_at", label: "Updated", field: "updated_at" },
                { name: "id", label: "ID", field: "id" },
                { name: "secret", label: "Secret", field: "secret" },
                {
                    name: "actions",
                    label: "Actions",
                    field: "actions",
                    align: "right",
                },
            ],
        };
    },

    watch: {
        "search.page"() {
            this.getClients();
        },
        "search.per_page"(value) {
            if (value) {
                this.getClients();
            }
        },
    },

    created() {
        this.getClients();
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getClients(event);
        },

        getClients(param = null) {
            const params = { ...this.search, ...param };

            this.$server
                .get(this.$page.props.route, { params })
                .then((res) => {
                    this.clients = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch(() => {});
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
