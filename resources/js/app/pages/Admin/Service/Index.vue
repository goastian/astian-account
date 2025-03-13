<template>
    <q-page>
        <q-table
            flat
            bordered
            :rows="services"
            :columns="headers"
            row-key="name"
            hide-bottom
            :rows-per-page-options="[search.per_page]"
            hide-pagination
        >
            <template v-slot:top>
                <h5>List of services</h5>
                <q-space />
                <v-create @created="getServices()"></v-create>
            </template>
            <template v-slot:body-cell-revoked="props">
                <q-td>
                    {{ props.row.revoked ? "Yes" : "No" }}
                </q-td>
            </template>
            <template v-slot:body-cell-actions="props">
                <q-td class="">
                    <v-update
                        @updated="getServices"
                        :item="props.row"
                    ></v-update>

                    <v-delete
                        @deleted="getServices"
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
            services: [],
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
                    label: "Group",
                    name: "group_name",
                    field: "group_name",
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
            this.getServices();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getServices();
            }
        },
    },

    created() {
        this.getServices();
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
                .get("/api/admin/services", {
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
