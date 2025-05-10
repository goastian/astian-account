<template>
    <v-user-layout>
        <q-toolbar>
            <q-toolbar-title> List of clients </q-toolbar-title>
            <v-create @created="getClients()"></v-create>
        </q-toolbar>
        <div class="row q-col-gutter-md">
            <div
                class="col-xs-12 col-md-4 col-lg-3 q-gutter-sm"
                v-for="client in clients"
                :key="client.id"
            >
                <q-card flat bordered>
                    <q-card-section>
                        <div class="text-h6">{{ client.name }}</div>
                        <div class="text-caption text-grey">
                            Created: {{ client.created_at }}
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-section class="q-gutter-sm">
                        <div>
                            <q-chip
                                clickable
                                @click="copyToClipboard(client.id)"
                                color="green"
                                text-color="white"
                                icon="mdi-content-copy"
                                label="*****"
                            >
                                <q-tooltip>Copy ID</q-tooltip>
                            </q-chip>
                        </div>

                        <div v-if="client.secret">
                            <q-chip
                                clickable
                                @click="copyToClipboard(client.secret)"
                                color="green"
                                text-color="white"
                                icon="mdi-content-copy"
                                label="*****"
                            >
                                <q-tooltip>Copy Secret</q-tooltip>
                            </q-chip>
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions align="right">
                        <v-update :item="client" @updated="getClients" />
                        <v-delete :item="client" @deleted="getClients" />
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
    </v-user-layout>
</template>

<script>
import VUserLayout from "../../UserLayout.vue";
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
        VUserLayout,
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
        const values = this.$page.props.clients;
        this.clients = values.data;
        this.pages = values.meta.pagination;
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
