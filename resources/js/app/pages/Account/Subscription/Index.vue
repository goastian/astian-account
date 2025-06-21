<template>
    <v-user-layout>
        <q-page padding>
            <div class="q-gutter-y-md q-pa-md">
                <q-toolbar>
                    <q-toolbar-title>
                        <q-icon name="list_alt" class="q-mr-sm" />
                        List of Packages
                    </q-toolbar-title>
                </q-toolbar>
            </div>

            <div class="q-pa-md">
                <q-table title="Your Packages" :rows="packages" :columns="columns" row-key="id" flat bordered
                    hide-bottom :rows-per-page-options="[search.per_page]">
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
                            <q-badge :color="props.row.status === 'successful'
                                    ? 'green'
                                    : 'orange'
                                " text-color="white" align="middle">
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
                <q-pagination v-model="search.page" color="grey-8" :max="pages.total_pages" size="md" direction-links
                    boundary-numbers />
            </div>
        </q-page>
    </v-user-layout>
</template>

<script>
import VDetail from "./Detail.vue";
import VCancel from './Cancel.vue';

export default {
    components: {
        VDetail,
        VCancel,
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
                per_page: 10,
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
        async getPackages(param = null) {
            var params = {};
            Object.assign(params, this.search);
            Object.assign(params, param);
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

        getIcon(service) {
            return this.icons.filter(data => data.name == service.meta.scopes[0].service.name);
        }
    },
};
</script>

<style scoped>
.card {
    gap: 1rem;
    width: 100%;
    background-color: var(--q-background-primary);
}

.name {
    color: var(--q-color-secondary);
}

.description {
    color: var(--q-color);
}

.icon {
    width: 40px;
    height: 40px;
    background-color: var(--q-color-blue-light);
    font-size: 1.5rem;
    padding: .2rem;
    border-radius: .4rem;
}

.meta-name {
    font-size: 1.2rem;
    line-height: 1.7rem;
    font-weight: 600;
}

.tag {
    background-color: var(--q-color-yellow-light);
    color: var(--q-color-yellow);
    padding: .1rem 1rem;
    border-radius: 1rem;
    border: 1px solid var(--q-color-yellow);
    font-size: .7rem;
}

.tag.danger {
    background-color: var(--q-color-red-light);
    color: var(--q-color-red);
    border-color: var(--q-color-red);
}

.tag.success {
    background-color: var(--q-color-green-light);
    color: var(--q-color-green);
    border-color: var(--q-color-green);
}
</style>
