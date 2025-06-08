<template>
    <v-admin-layout>
        <q-toolbar>
            <q-toolbar-title> List of plans </q-toolbar-title>
            <v-create @created="getPlans" />
        </q-toolbar>

        <div class="q-pa-sm">
            <div class="row">
                <div
                    v-for="plan in plans"
                    :key="plan.id"
                    class="col-12 col-md-6 col-md-3 q-gutter-sm q-pa-sm"
                >
                    <q-card flat bordered>
                        <q-card-section>
                            <div class="text-h6 flex">
                                {{ plan.name }}
                                <q-space />
                                <v-update @updated="getPlans" :item="plan" />
                                <v-delete @deleted="getPlans" :item="plan" />
                            </div>
                            <div class="text-subtitle1">
                                <span v-html="plan.description"></span>
                            </div>
                            <div class="q-mt-sm q-gutter-sm">
                                <q-badge
                                    :color="
                                        plan.active ? 'primary' : 'secondary'
                                    "
                                >
                                    Active : {{ plan.active ? "Yes" : "No" }}
                                </q-badge>
                                <q-badge
                                    color="green"
                                    v-if="plan.bonus_enabled"
                                    class="q-ml-sm"
                                >
                                    Bonus: {{ plan.bonus_duration }} days
                                </q-badge>
                                <q-badge
                                    color="green"
                                    v-if="plan.trial_enabled"
                                    class="q-ml-sm"
                                >
                                    Trial: {{ plan.trial_duration }} days
                                </q-badge>
                            </div>
                        </q-card-section>
                        <q-card-section>
                            <div class="text-grey-7 text-subtitle2 q-mb-xs">
                                <q-icon name="vpn_key" class="q-mr-sm" /> Scopes
                            </div>
                            <div v-if="plan.scopes.length">
                                <q-list dense bordered padding>
                                    <q-expansion-item
                                        v-for="(item, index) in plan.scopes"
                                        :key="index"
                                        expand-icon="keyboard_arrow_down"
                                        :label="`Service : ${item.service.name} - ${item.role.name}`"
                                        :caption="`Group : ${item.service.group.name}`"
                                        expand-separator
                                        dense
                                    >
                                        <q-item>
                                            <q-item-section>
                                                <q-badge
                                                    color="primary"
                                                    class="q-mr-sm"
                                                >
                                                    Role
                                                </q-badge>
                                                <span>{{
                                                    item.role.name
                                                }}</span>
                                                <div
                                                    class="text-caption text-grey-7"
                                                >
                                                    {{ item.role.description }}
                                                </div>
                                                <div>
                                                    <v-revoke-scope
                                                        @revoked="getPlans"
                                                        :item="item"
                                                    />
                                                </div>
                                            </q-item-section>
                                        </q-item>
                                    </q-expansion-item>
                                </q-list>
                            </div>
                            <div v-else class="text-grey">
                                No scopes assigned
                            </div>
                        </q-card-section>

                        <q-card-section>
                            <div class="text-grey-7 text-subtitle2">Prices</div>

                            <q-list dense bordered class="q-mt-sm">
                                <q-item
                                    v-for="(item, index) in plan.prices"
                                    :key="index"
                                    class="q-my-xs q-px-sm"
                                >
                                    <q-item-section side>
                                        <q-badge color="primary" outline>
                                            {{ item.billing_period }}
                                        </q-badge>
                                    </q-item-section>

                                    <q-item-section>
                                        <div>
                                            <span class="text-weight-medium">{{
                                                item.currency
                                            }}</span>
                                            <span class="q-ml-sm">{{
                                                item.amount_format
                                            }}</span>
                                        </div>
                                        <div class="text-caption text-grey">
                                            Expiration:
                                            {{ item.expiration || "—" }}
                                        </div>
                                    </q-item-section>

                                    <q-item-section side>
                                        <v-delete-price
                                            :item="item"
                                            @deleted="getPlans"
                                        />
                                    </q-item-section>
                                </q-item>
                            </q-list>
                        </q-card-section>

                        <q-separator />

                        <q-card-section class="text-caption">
                            Created: {{ plan.created }} <br />
                            Updated: {{ plan.updated }}
                        </q-card-section>
                    </q-card>
                </div>
            </div>
        </div>

        <div class="row justify-center q-mt-md">
            <q-pagination
                v-model="search.page"
                color="grey-8"
                :max="pages.total_pages"
                size="sm"
            />
        </div>
    </v-admin-layout>
</template>

<script>
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";
import VUpdate from "./Updated.vue";
import VRevokeScope from "./RevokeScope.vue";
import VDeletePrice from "./DeletePrice.vue";

export default {
    components: {
        VCreate,
        VDelete,
        VUpdate,
        VRevokeScope,
        VDeletePrice,
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
            plans: [],
        };
    },

    created() {
        const values = this.$page.props.plans;
        this.plans = values.data;
        this.pages = values.meta.pagination;
    },

    methods: {
        async getPlans() {
            try {
                const res = await this.$server.get(
                    this.$page.props.route.plans
                );

                if (res.status == 200) {
                    this.plans = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (error) {}
        },
    },
};
</script>
