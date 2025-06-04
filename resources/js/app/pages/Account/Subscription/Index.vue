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

                <div class="q-pa-md">
                    <div class="row q-col-gutter-md q-row-gutter-md">
                        <div
                            v-for="(item, index) in packages"
                            :key="index"
                            class="col-12 col-sm-6 col-md-4 col-lg-3"
                        >
                            <q-card bordered class="shadow-2 q-pa-md column card">
                                <div
                                    class="flex items-start justify-between"
                                >
                                    <div class="row items-center q-gutter-x-md">
                                        <template
                                            v-if="getIcon(item).length == 1"
                                            v-for="(ic, idx) in icons"
                                        >
                                            <div
                                                class="icon flex justify-center"
                                                v-if="item.meta.scopes[0].service.name == ic.name"
                                            >
                                                <span
                                                >{{ ic.icon }}</span>
                                            </div>
                                        </template>
                                        <div
                                            class="icon"
                                            v-else
                                        >
                                            <span>
                                                📦
                                            </span>
                                        </div>
                                        <div>
                                            <div class="meta-name description">
                                                {{ item?.meta.name }}
                                            </div>
                                            <div class="text-caption name">
                                                {{ item.transaction.billing_period }}
                                                plan
                                            </div>
                                        </div>
                                    </div>
                                    <span
                                        class="tag"
                                        :class="[{'danger' : item.status == 'cancelled'}, {'success' : item.status == 'successful'}]"
                                    >{{ item.status }}</span>
                                </div>

                                <div class="q-pt-none">
                                    <div class="q-mb-sm row justify-between">
                                        <div class="name">
                                            <q-icon name="payments" class="q-mr-xs" />
                                            <span>Price</span>
                                        </div>
                                        <span class="description">
                                            {{ item.transaction.total }}
                                            {{ item.transaction.currency }}
                                        </span>
                                    </div>

                                    <div class="q-mb-sm row justify-between" v-if="item.meta.bonus_enabled">
                                        <div class="name">
                                            <q-icon name="card_giftcard" class="q-mr-xs" />
                                            <span>Bonus</span>
                                        </div>
                                        <span class="description">
                                            {{ item.meta.bonus_duration }} days
                                        </span>
                                    </div>

                                    <div class="q-mb-sm row justify-between">
                                        <div class="name">
                                            <q-icon name="event" class="q-mr-xs" />
                                            <span>Start</span>
                                        </div>
                                        <span v-if="item.start_at" class="description">{{ item.start_at}}</span>
                                        <span v-else class="description">-</span>
                                    </div>

                                    <div class="q-mb-sm row justify-between">
                                        <div class="name">
                                            <q-icon
                                                name="event_available"
                                                class="q-mr-xs"
                                            />
                                            <span>Next Renewal</span>
                                        </div>
                                        <span v-if="item.end_at" class="description">{{ item.end_at }}</span>
                                        <span v-else class="description">-</span>
                                    </div>

                                    <div class="q-mb-sm row justify-between">
                                        <div class="name">
                                            <q-icon name="credit_card" class="q-mr-xs" />
                                            <span>Method</span>
                                        </div>
                                        <span class="description">
                                            {{ item.transaction.payment_method }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row justify-between q-gutter-x-md">
                                    <v-detail :item="item" @reload="getPackages" />
                                    <v-cancel
                                        v-if="item.status === 'pending'"
                                        :item="item.transactions[0]"
                                        @canceled="getPackages"
                                    />
                                </div>
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
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 10,
            },
            packages: [],
            icons: [
                {
                    name: 'vpn',
                    icon: '🛡️',
                },
                {
                    name: 'cloud',
                    icon: '☁️',
                },
            ],
            count: 0,
        };
    },

    mounted() {
        const values = this.$page.props.packages;
        this.packages = values.data;
        this.pages = values.meta.pagination;
        console.log(this.packages);
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
                    this.$page.props.user.links.subscriptions, {
                        params: params
                    }
                );
                if (res.status == 200) {
                    this.packages = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (error) {}
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
