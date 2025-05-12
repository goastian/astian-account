<template>
    <v-user-layout>
        <q-toolbar>
            <q-toolbar-title>
                <q-icon name="list_alt" class="q-mr-sm" />
                List of Packages
            </q-toolbar-title>
        </q-toolbar>

        <div class="q-pa-md">
            <div class="row q-col-gutter-md q-row-gutter-md">
                <div
                    v-for="(item, index) in packages"
                    :key="index"
                    class="col-12 col-sm-6 col-md-4 col-lg-3"
                >
                    <q-card bordered class="shadow-2">
                        <q-card-section
                            class="flex align-center justify-content-between"
                        >
                            <div>
                                <div class="text-h6 text-primary">
                                    {{ item?.meta.name }}
                                </div>
                                <div class="text-caption text-grey">
                                    {{ item.transaction.billing_period }}
                                    plan
                                </div>
                            </div>
                            <q-space></q-space>
                            <v-detail :item="item" @reload="getPackages" />
                        </q-card-section>

                        <q-separator />

                        <q-card-section class="q-pt-none">
                            <div class="q-mb-sm">
                                <q-icon name="payments" class="q-mr-xs" />
                                <strong>Price:</strong>
                                {{ item.transaction.total }}
                                {{ item.transaction.currency }}
                            </div>

                            <div class="q-mb-sm" v-if="item.meta.bonus_enabled">
                                <q-icon name="card_giftcard" class="q-mr-xs" />
                                <strong>Bonus:</strong>
                                {{ item.meta.bonus_duration }} days
                            </div>

                            <div class="q-mb-sm">
                                <q-icon name="event" class="q-mr-xs" />
                                <strong>Start:</strong> {{ item.start_at }}
                            </div>

                            <div class="q-mb-sm">
                                <q-icon
                                    name="event_available"
                                    class="q-mr-xs"
                                />
                                <strong>End:</strong> {{ item.end_at }}
                            </div>

                            <div class="q-mb-sm">
                                <q-icon name="credit_card" class="q-mr-xs" />
                                <strong>Method:</strong>
                                {{ item.transaction.payment_method }}
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
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            packages: [],
        };
    },

    mounted() {
        const values = this.$page.props.packages;
        this.packages = values.data;
        this.pages = values.meta.pagination;
    },

    methods: {
        async getPackages() {
            try {
                const res = await this.$server.get(
                    this.$user.links.subscriptions
                );

                if (res.status == 200) {
                    this.packages = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (error) {}
        },
    },
};
</script>
