<template>
    <q-table
        flat
        bordered
        :rows="scopes"
        :columns="headers"
        row-key="name"
        hide-bottom
        :rows-per-page-options="[search.per_page]"
        hide-pagination
    >
        <template v-slot:top>
            <h5>List of scopes</h5>
            <q-space />
            <v-create @created="getScopes()"></v-create>
        </template>
        <template v-slot:body-cell-revoked="props">
            <q-td>
                {{ props.row.revoked ? "Yes" : "No" }}
            </q-td>
        </template>
        <template v-slot:body-cell-actions="props">
            <q-td class="">
                <v-update
                    @updated="getScopes"
                    :item="props.row"
                ></v-update>

                <v-delete
                    @deleted="getScopes"
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
            scopes: [],
            headers: [
                {label: "Group-Service-Role", name: "value", field: "gsr_id", align: "left"},
                { label: "Service Description", name: "value", field: "service_description", align: "left" },
                {
                    label: "Role Description",
                    name: "system",
                    field: "role_description",
                    align: "left",
                },
                {
                    label: "Public",
                    name: "public",
                    field: "public",
                    align: "left",
                },
                {
                    label: "Active",
                    name: "active",
                    field: "active",
                    align: "left"
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
            this.getScopes();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getScopes();
            }
        },
    },

    created() {
        this.getScopes();
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getScopes(event);
        },

        getScopes(param = null) {
            var params = {};
            Object.assign(params, this.search);
            Object.assign(params, param);

            this.$server
                .get("/api/admin/scopes", {
                    params: params,
                })
                .then((res) => {
                    this.scopes = res.data.data;
                    var meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch((e) => {});
        },
    },
};
</script>
