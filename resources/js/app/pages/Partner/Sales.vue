<template>
    <v-partner-layout>
        <q-page padding>
            <q-card>
                <q-card-section>
                    <div class="text-h6">My sales</div>
                </q-card-section>

                <q-table
                    :rows="sales"
                    :columns="columns"
                    row-key="id"
                    flat
                    bordered
                    dense
                    hide-bottom
                    :rows-per-page-options="[search.per_page]"
                >
                    <template v-slot:body-cell-total="props">
                        <q-td :props="props">
                            {{
                                (
                                    (parseFloat(props.row.total || 0) *
                                        parseFloat(
                                            props.row.partner_commission_rate ||
                                                0
                                        )) /
                                    100
                                ).toFixed(2)
                            }}
                        </q-td>
                    </template>

                    <template v-slot:body-cell-status="props">
                        <q-td :props="props">
                            <q-badge color="primary" align="middle">{{
                                props.value
                            }}</q-badge>
                        </q-td>
                    </template>
                </q-table>
            </q-card>

            <div class="row justify-center q-mt-md">
                <q-pagination
                    v-model="search.page"
                    color="grey-8"
                    :max="pages.total_pages"
                    size="sm"
                />
            </div>
        </q-page>
    </v-partner-layout>
</template>

<script>
export default {
    data() {
        return {
            sales: [],
            columns: [
                {
                    name: "currency",
                    label: "Currency",
                    field: "currency",
                    align: "left",
                },
                {
                    name: "status",
                    label: "Status",
                    field: "status",
                    align: "left",
                },
                {
                    name: "total",
                    label: "Total",
                    field: "total",
                    align: "right",
                },
                {
                    name: "created",
                    label: "Created",
                    field: "created",
                    align: "left",
                },
                {
                    name: "updated",
                    label: "Updated",
                    field: "updated",
                    align: "left",
                },
            ],

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
            this.getSales();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getSales();
            }
        },
    },

    created() {
        this.getSales();
    },

    methods: {
        async getSales(param = null) {
            var params = { ...this.search, ...param };

            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: params,
                });

                if (res.status == 200) {
                    var values = res.data;
                    this.sales = values.data;
                    this.pages = res.data.meta.pagination;
                    this.search.total_pages =
                        res.data.meta.pagination.total_pages;
                }
            } catch (error) {}
        },
    },
};
</script>
