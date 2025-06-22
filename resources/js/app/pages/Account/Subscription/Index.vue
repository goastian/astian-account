<template>
    <v-user-layout>
        <q-toolbar>
            <q-toolbar-title>
                <q-icon name="list_alt" class="q-mr-sm" />
                List of packages
            </q-toolbar-title>
        </q-toolbar>

        <div class="q-pa-md">
            <q-table
                :rows="packages"
                :columns="columns"
                row-key="id"
                flat
                bordered
                hide-bottom
                :rows-per-page-options="[search.per_page]"
            >
                <template v-slot:body-cell-name="props">
                    <q-td>
                        <div class="text-weight-medium text-primary">
                            {{ props.row.meta.name }}
                        </div>
                        <div class="text-caption text-grey">
                            {{ props.row.transaction.billing_period }} plan
                        </div>
                    </q-td>
                </template>

                <template v-slot:body-cell-price="props">
                    <q-td>
                        {{ props.row.transaction.total }}
                        {{ props.row.transaction.currency }}
                    </q-td>
                </template>

                <template v-slot:body-cell-bonus="props">
                    <q-td>
                        <div v-if="props.row.meta.bonus_enabled">
                            🎁 {{ props.row.meta.bonus_duration }} days
                        </div>
                        <div v-else class="text-grey">—</div>
                    </q-td>
                </template>

                <template v-slot:body-cell-start="props">
                    <q-td>{{ props.row.start_at }}</q-td>
                </template>

                <template v-slot:body-cell-end="props">
                    <q-td>{{ props.row.end_at }}</q-td>
                </template>

                <template v-slot:body-cell-method="props">
                    <q-td>{{ props.row.transaction.payment_method }}</q-td>
                </template>

                <template v-slot:body-cell-status="props">
                    <q-td>
                        <q-badge
                            :color="
                                props.row.status === 'successful'
                                    ? 'green'
                                    : 'orange'
                            "
                            text-color="white"
                            align="middle"
                        >
                            {{ props.row.status }}
                        </q-badge>
                    </q-td>
                </template>

                <template v-slot:body-cell-actions="props">
                    <q-td>
                        <v-detail :item="props.row" @reload="getPackages" />
                    </q-td>
                </template>
            </q-table>
        </div>

        <div class="row justify-center q-mt-md">
            <q-pagination
                v-model="search.page"
                color="grey-8"
                :max="pages.total_pages"
                size="md"
                direction-links
                boundary-numbers
            />
        </div>
    </v-user-layout>
</template>

<script>
import VDetail from "./Detail.vue";

export default {
    components: {
        VDetail,
    },

    data() {
        return {
            loading: false,
            user: [],
            packages: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            columns: [
                {
                    name: "name",
                    label: "Name",
                    field: "meta.name",
                    align: "left",
                },
                { name: "price", label: "Price", align: "left" },
                { name: "bonus", label: "Bonus", align: "left" },
                { name: "start", label: "Start", align: "left" },
                { name: "end", label: "End", align: "left" },
                { name: "method", label: "Method", align: "left" },
                { name: "status", label: "Status", align: "center" },
                { name: "actions", label: "", align: "center" },
            ],
        };
    },

    created() {
        this.user = this.$page.props.user;

        this.getPackages();
    },

    watch: {
        "search.page"(value) {
            this.getPackages();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getPackages();
            }
        },
    },

    methods: {
        async getPackages() {
            try {
                const res = await this.$server.get(
                    this.user.links.subscriptions,
                    {
                        params: this.search,
                    }
                );

                if (res.status === 200) {
                    this.packages = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (error) {
                console.error(error);
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
