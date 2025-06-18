<template>
    <v-user-layout>
        <q-toolbar>
            <q-toolbar-title>List of clients</q-toolbar-title>
            <v-create @created="getClients()"></v-create>
        </q-toolbar>

        <q-table
            flat
            bordered
            :rows="clients"
            :columns="columns"
            row-key="id"
            hide-bottom
            :rows-per-page-options="[search.per_page]"
        >
            <template v-slot:body-cell-credentials="props">
                <q-td :props="props">
                    <div class="q-gutter-xs">
                        <q-chip
                            clickable
                            @click="copyToClipboard(props.row.id)"
                            color="green"
                            text-color="white"
                            icon="mdi-content-copy"
                            label="ID"
                        >
                            <q-tooltip>Copy ID</q-tooltip>
                        </q-chip>
                        <q-chip
                            v-if="props.row.secret"
                            clickable
                            @click="copyToClipboard(props.row.secret)"
                            color="green"
                            text-color="white"
                            icon="mdi-content-copy"
                            label="Secret"
                        >
                            <q-tooltip>Copy Secret</q-tooltip>
                        </q-chip>
                    </div>
                </q-td>
            </template>

            <template v-slot:body-cell-actions="props">
                <q-td :props="props">
                    <div class="q-gutter-xs">
                        <v-update :item="props.row" @updated="getClients" />
                        <v-delete :item="props.row" @deleted="getClients" />
                    </div>
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
    </v-user-layout>
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
            clients: [],
            pages: {
                total_pages: 1,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            columns: [
                {
                    name: "name",
                    required: true,
                    label: "Name",
                    align: "left",
                    field: (row) => row.name,
                    sortable: true,
                },
                {
                    name: "created_at",
                    label: "Created",
                    align: "left",
                    field: (row) => row.created_at,
                    sortable: true,
                },
                {
                    name: "credentials",
                    label: "Credentials",
                    align: "center",
                    field: (row) => row.id,
                },
                {
                    name: "actions",
                    label: "Actions",
                    align: "right",
                    field: (row) => row.id,
                },
            ],
        };
    },

    watch: {
        "search.page"(value) {
            this.getClients();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
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
            this.getUsers(event);
        },

        getClients(param = null) {
            var params = {};
            Object.assign(params, this.search);
            Object.assign(params, param);

            this.$server
                .get(this.$page.props.route, {
                    params: params,
                })
                .then((res) => {
                    this.clients = res.data.data;
                    var meta = res.data.meta;
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
