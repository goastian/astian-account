<template>
    <v-admin-layout>
        <v-filter :params="params" @change="searching" />
        <q-toolbar>
            <q-toolbar-title>
                <q-icon name="list_alt" class="q-mr-sm" />
                List of transactions
            </q-toolbar-title>
        </q-toolbar>

        <div class="q-pa-md">
            <div class="row q-col-gutter-md q-row-gutter-md">
                <div
                    v-for="(item, index) in transactions"
                    :key="index"
                    class="col-12 col-sm-6 col-md-4 col-lg-3"
                >
                    <q-card bordered class="shadow-2">
                        <q-card-section
                            class="flex align-center justify-content-between"
                        >
                            <div class="text-h6 text-primary">
                                {{ item.billing_period }} plan
                            </div>
                            <q-space />
                            <v-activate
                                @updated="getTransactions"
                                v-if="check(item)"
                                :item="item"
                            />
                            <q-space />
                            <v-detail :item="item" />
                        </q-card-section>

                        <q-separator />

                        <q-card-section class="q-pt-none">
                            <div class="q-mb-sm">
                                <q-icon name="event" class="q-mr-xs" />
                                <strong>code:</strong> {{ item.code }}
                            </div>
                            <div class="q-mb-sm">
                                <q-icon name="payments" class="q-mr-xs" />
                                <strong>Price:</strong>
                                {{ item.total }}
                                {{ item.currency }}
                            </div>
                            <div class="q-mb-sm">
                                <q-icon name="event" class="q-mr-xs" />
                                <strong>Created:</strong> {{ item.created }}
                            </div>

                            <div class="q-mb-sm">
                                <q-icon
                                    name="event_available"
                                    class="q-mr-xs"
                                />
                                <strong>Updated:</strong> {{ item.updated }}
                            </div>

                            <div class="q-mb-sm">
                                <q-icon name="credit_card" class="q-mr-xs" />
                                <strong>Method:</strong>
                                {{ item.payment_method }}
                            </div>

                            <div class="q-mb-sm">
                                <q-icon name="check_circle" class="q-mr-xs" />
                                <strong>Status:</strong>
                                <q-badge
                                    :color="
                                        item.status === 'successful'
                                            ? 'green'
                                            : 'orange'
                                    "
                                    text-color="white"
                                    align="middle"
                                >
                                    {{ item.status }}
                                </q-badge>
                            </div>
                            <div class="q-mb-sm">
                                <q-icon name="event" class="q-mr-xs" />
                                <strong>Activated :</strong>
                                {{ item.activated }}
                            </div>
                        </q-card-section>
                    </q-card>
                </div>
            </div>
        </div>

        <div class="row justify-center q-mt-md">
            <q-pagination
                v-model="search.page"
                color="primary"
                :max="pages.total_pages"
                size="md"
                direction-links
                boundary-numbers
            />
        </div>
    </v-admin-layout>
</template>
<script>
import VAdminLayout from "../../AdminLayout.vue";
import VActivate from "./Activate.vue";
import VDetail from "./Detail.vue";

export default {
    components: {
        VActivate,
        VDetail,
        VAdminLayout,
    },

    data() {
        return {
            params: [
                { key: "Code", value: "code" },
                { key: "Session", value: "session_id" },
                { key: "Intent", value: "payment_intent_id" },
                { key: "Created", value: "created" },
                { key: "Updated", value: "updated" },
            ],
            transactions: [],
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
            this.getTransactions();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getTransactions();
            }
        },
    },

    created() {
        const values = this.$page.props.transactions;
        this.transactions = values.data;
        this.pages = values.meta.pagination;
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getTransactions(event);
        },

        async getTransactions(param = null) {
            var params = {};
            Object.assign(params, this.search);
            Object.assign(params, param);

            try {
                const res = await this.$server.get("/admin/transactions", {
                    params: params,
                });
                if (res.status == 200) {
                    this.transactions = res.data.data;
                }
            } catch (error) {}
        },

        check(item) {
            return item.status == "pending" || item.status == "failed";
        },
    },
};
</script>
