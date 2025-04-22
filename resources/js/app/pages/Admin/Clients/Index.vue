<template>
    <div>
        <q-toolbar class="q-ma-sm">
            <q-toolbar-title> List of clients </q-toolbar-title>

            <v-create @created="getClients()" />
        </q-toolbar>

        <div class="row q-col-gutter-md q-ma-sm">
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
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            snackbar: false,
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
            this.getClients(event);
        },

        getClients(param = null) {
            var params = {};
            Object.assign(params, this.search);
            Object.assign(params, param);

            this.$server
                .get("/api/admin/clients", {
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
