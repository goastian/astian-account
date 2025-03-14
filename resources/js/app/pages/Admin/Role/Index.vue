<template>
    <q-page>
        <v-filter :params="params" @change="searching"></v-filter>
        <q-table
            flat
            bordered
            :rows="roles"
            :columns="headers"
            row-key="name"
            hide-bottom
            :rows-per-page-options="[search.per_page]"
            hide-pagination
        >
            <template v-slot:top>
                <h5>List of roles</h5>
                <q-space />
                <v-create @created="getRoles()"></v-create>
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
                        @updated="getRoles"
                        :item="props.row"
                    ></v-update>

                    <v-delete
                        @deleted="getRoles"
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
    </q-page>
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
            roles: [],
            headers: [
                { label: "Name", name: "value", field: "name", align: "left" },
                { label: "Slug", name: "value", field: "slug", align: "left" },
                {
                    label: "Description",
                    name: "description",
                    field: "description",
                    align: "left",
                },
                {
                    label: "System",
                    name: "system",
                    field: "system",
                    align: "left",
                },
                {
                    label: "Actions",
                    name: "actions",
                    field: "actions",
                    align: "center",
                },
            ],
            params: [
                { key: "Name", value: "name" },
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
            this.getRoles();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getRoles();
            }
        },
    },

    created() {
        this.getRoles();
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getRoles(event);
        },

        getRoles(param = null) {
            var params = {};
            Object.assign(params, this.search);
            Object.assign(params, param);

            this.$server
                .get("/api/admin/roles", {
                    params: params,
                })
                .then((res) => {
                    this.roles = res.data.data;
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
