<template>
    <q-table
        flat
        bordered
        :rows="clients"
        :columns="headers"
        row-key="name"
        hide-bottom
        :rows-per-page-options="[search.per_page]"
        hide-pagination
    >
        <template v-slot:top>
            <h5>List of clients</h5>
            <q-space />
            <v-create @created="getClients()"></v-create>
        </template>
        <template v-slot:body-cell-id="props">
            <q-td>
                <q-chip
                    clickable
                    @click="copyToClipboard(props.row.id)"
                    color="green"
                    text-color="white"
                    icon="mdi-content-copy"
                    label="*****"
                >
                    <q-tooltip> Copy id </q-tooltip>
                </q-chip>
            </q-td>
        </template>
        <template v-slot:body-cell-secret="props">
            <q-td>
                <q-chip
                    v-if="props.row.secret"
                    clickable
                    @click="copyToClipboard(props.row.secret)"
                    color="green"
                    text-color="white"
                    icon="mdi-content-copy"
                    label="*****"
                >
                    <q-tooltip> Copy secret </q-tooltip>
                </q-chip>
            </q-td>
        </template>
        <template v-slot:body-cell-revoked="props">
            <q-td>
                {{ props.row.revoked ? "Yes" : "No" }}
            </q-td>
        </template>
        <template v-slot:body-cell-actions="props">
            <q-td class="">
                <v-update
                    @updated="getClients"
                    :item="props.row"
                ></v-update>

                <v-delete
                    @deleted="getClients"
                    :item="props.row"
                ></v-delete>
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
            headers: [
                { label: "Name", name: "value", field: "name", align: "left" },
                { label: "Identifier", name: "id", field: "id", align: "left" },
                {
                    label: "Secret",
                    name: "secret",
                    field: "secret",
                    align: "left",
                },
                {
                    label: "Revoked",
                    name: "revoked",
                    field: "revoked",
                    align: "left",
                },
                {
                    label: "Created",
                    name: "created_at",
                    field: "created_at",
                    align: "left",
                },
                {
                    label: "Updated",
                    name: "updated_at",
                    field: "updated_at",
                    align: "left",
                },
                {
                    label: "Actions",
                    name: "actions",
                    field: "actions",
                    align: "center",
                },
            ],
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
            this.getUsers(event);
        },

        getClients(param = null) {
            var params = {};
            Object.assign(params, this.search);
            Object.assign(params, param);

            this.$server
                .get("/oauth/clients", {
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
