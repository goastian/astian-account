<template>
    <div class="q-pa-md q-gutter-sm">
        <q-btn round outline color="positive" @click="open" icon="mdi-plus-circle">
            <q-tooltip transition-show="rotate" transition-hide="rotate">
                Add new plan
            </q-tooltip>
        </q-btn>

        <q-dialog v-model="dialog" persistent full-width>
            <q-card class="w-100 py-4">
                <q-card-section class="text-center">
                    <h6 class="text-h6 text-grey-8">Add New Plan</h6>
                </q-card-section>

                <!-- Formulario principal -->
                <q-card-section>
                    <div class="q-gutter-y-md">
                        <!-- Nombre del plan -->
                        <q-input outlined v-model="form.name" label="Name" :error="!!errors.name">
                            <template v-slot:error>
                                <v-error :error="errors.name" />
                            </template>
                        </q-input>

                        <!-- Editor de contenido -->
                        <v-editor @content="setContent" />

                        <!-- Opciones de activación, bonus y trial -->
                        <div class="row q-col-gutter-md">
                            <!-- Active -->
                            <q-item tag="label" v-ripple class="col-xs-12 col-md-4">
                                <q-item-section avatar>
                                    <q-checkbox v-model="form.active" color="orange" :error="!!errors.active" />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label>Active</q-item-label>
                                    <q-item-label caption>Enable plan availability</q-item-label>
                                </q-item-section>
                            </q-item>

                            <!-- Bonus enabled -->
                            <q-item tag="label" v-ripple class="col-xs-12 col-md-4">
                                <q-item-section avatar>
                                    <q-checkbox v-model="form.bonus_enabled" color="orange"
                                        :error="!!errors.bonus_enabled" />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label>Bonus enabled</q-item-label>
                                    <q-item-label caption>Enable an additional bonus for the
                                        plan</q-item-label>
                                </q-item-section>
                            </q-item>

                            <!-- Bonus duration -->
                            <q-input class="col-xs-12 col-md-4" v-model="form.bonus_duration" label="Bonus duration"
                                type="number" outlined :error="!!errors.bonus_duration">
                                <template v-slot:error>
                                    <v-error :error="errors.bonus_duration" />
                                </template>
                            </q-input>

                            <!-- Trial enabled -->
                            <q-item tag="label" v-ripple class="col-xs-12 col-md-4">
                                <q-item-section avatar>
                                    <q-checkbox v-model="form.trial_enabled" color="orange"
                                        :error="!!errors.trial_enabled" />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label>Trial enabled</q-item-label>
                                    <q-item-label caption>Enable an additional trial for the
                                        plan</q-item-label>
                                </q-item-section>
                            </q-item>

                            <!-- Trial duration -->
                            <q-input class="col-xs-12 col-md-4" v-model="form.trial_duration" label="Trial duration"
                                type="number" outlined :error="!!errors.trial_duration">
                                <template v-slot:error>
                                    <v-error :error="errors.trial_duration" />
                                </template>
                            </q-input>
                        </div>
                    </div>
                </q-card-section>

                <!-- Sección de precios -->
                <q-card-section>
                    <div class="text-subtitle2 q-mb-sm">Prices</div>
                    <v-error :error="errors.prices" />

                    <div v-for="(price, index) in form.prices" :key="index" class="row q-col-gutter-md q-mb-md">
                        <q-select class="col-12 col-md-3" v-model="price.billing_period" :options="billing_periods"
                            emit-value map-options label="Billing Period"
                            :error="!!errors[`prices.${index}.billing_period`]">
                            <template v-slot:error>
                                <v-error :error="errors[`prices.${index}.billing_period`]
                                    " />
                            </template>
                        </q-select>

                        <q-select class="col-12 col-md-3" v-model="price.currency" :options="currencies" emit-value
                            label="Currency" :error="!!errors[`prices.${index}.currency`]">
                            <template v-slot:error>
                                <v-error :error="errors[`prices.${index}.currency`]" />
                            </template>
                        </q-select>

                        <q-input class="col-12 col-md-3" v-model.number="price.amount" label="Amount" type="number"
                            step="0.01" :error="!!errors[`prices.${index}.amount`]">
                            <template v-slot:error>
                                <v-error :error="errors[`prices.${index}.amount`]" />
                            </template>
                        </q-input>

                        <div class="col-12 col-md-3 flex justify-center items-center">
                            <q-btn icon="delete" color="negative" outline @click="form.prices.splice(index, 1)" />
                        </div>
                    </div>

                    <q-btn color="primary" icon="add" label="Add Price" @click="
                        form.prices.push({
                            billing_period: billing_periods.length
                                ? billing_periods[0].value
                                : '',
                            currency: currencies.length
                                ? currencies[0].value
                                : '',
                            amount: null,
                        })
                        " />
                </q-card-section>

                <!-- Scopes -->
                <q-card-section>
                    <div class="text-subtitle2 q-mb-sm">Choose Scopes</div>

                    <q-select filled v-model="service" :options="services" label="Service" color="teal" clearable
                        :error="!!errors.scopes" options-selected-class="text-deep-orange">
                        <template v-slot:option="scope">
                            <q-item v-bind="scope.itemProps">
                                <q-item-section avatar>
                                    <q-icon color="positive" name="mdi-check-all" />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label class="text-ucfirst">{{
                                        scope.opt.name
                                    }}</q-item-label>
                                    <q-item-label caption class="text-ucfirst">
                                        <q-icon color="positive" name="mdi-group" />
                                        {{ scope.opt.group.name }} <br />
                                        {{ scope.opt.group.description }}
                                    </q-item-label>
                                </q-item-section>
                            </q-item>
                        </template>

                        <template v-slot:selected>
                            <q-item v-if="service && service.name">
                                <q-item-section avatar>
                                    <q-icon color="positive" name="mdi-check-all" />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label class="text-ucfirst">{{
                                        service.name
                                    }}</q-item-label>
                                    <q-item-label caption class="text-ucfirst">
                                        <q-icon color="positive" name="mdi-group" />
                                        {{ service.group.name }}
                                    </q-item-label>
                                </q-item-section>
                            </q-item>
                        </template>

                        <template v-slot:error>
                            <v-error :error="errors.scopes" />
                        </template>
                    </q-select>

                    <!-- Lista de scopes -->
                    <q-list v-if="scopes.length" bordered padding class="rounded-borders q-mt-md bg-grey-1">
                        <q-item v-for="(item, index) in scopes" :key="index"
                            class="q-mb-sm q-pa-sm shadow-1 rounded-borders">
                            <q-item-section avatar>
                                <q-avatar rounded color="primary" text-color="white">
                                    <q-icon name="mdi-account-key" />
                                </q-avatar>
                            </q-item-section>

                            <q-item-section>
                                <div class="text-subtitle3 text-bold text-ucfirst">
                                    {{ item.role.name }}
                                </div>
                                <div class="text-caption text-grey-7">
                                    {{ item.role.description }}
                                </div>
                            </q-item-section>

                            <q-item-section side top>
                                <q-btn :icon="hasScope(item.id)
                                    ? 'mdi-trash-can-outline'
                                    : 'mdi-plus'
                                    " :color="hasScope(item.id)
                                        ? 'negative'
                                        : 'positive'
                                        " outline round dense @click="toggleScope(item.id)">
                                    <q-tooltip>
                                        {{
                                            hasScope(item.id)
                                                ? "Remove scope"
                                                : "Add scope"
                                        }}
                                    </q-tooltip>
                                </q-btn>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-card-section>

                <!-- Acciones -->
                <q-card-actions align="right">
                    <q-btn outline color="positive" label="Accept" @click="create" />
                    <q-btn outline color="secondary" label="Close" @click="close" />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>
<script>
export default {
    emits: ["created"],

    data() {
        return {
            dialog: false,
            form: {},
            errors: {},
            scopes: [],
            services: [],
            service: [],
            currencies: [],
            billing_periods: [],
        };
    },

    watch: {
        service(value) {
            this.getServicesScope();
        },
    },

    methods: {
        /**
         * Clean the form when it is closed
         */
        close() {
            this.dialog = false;
            this.clean();
        },

        setContent(text) {
            this.form.description = text;
        },

        clean() {
            this.form.name = "";
            this.form.description = "";
            this.form.active = false;
            this.form.bonus_enabled = false;
            this.form.bonus_duration = 0;
            this.form.trial_enabled = false;
            this.form.trial_duration = 0;
            this.form.scopes = [];
            this.form.prices = [];
            this.errors = {};
        },

        open() {
            this.clean();
            this.getServices();
            this.getBillingPeriod();
            this.getCurrencies();
            this.dialog = true;
        },

        /**
         * Create a new client
         */
        async create() {

            try {
                const res = await this.$server.post(
                    this.$page.props.route.plans,
                    this.form
                );

                if (res.status == 201) {
                    this.clean();
                    this.$emit("created", true);
                    this.dialog = false;
                    this.$q.notify({
                        type: "positive",
                        message: "New plan has been created successfully",
                        timeout: 3000,
                    });
                }
            } catch (e) {
                if (e.response && e.response.data.errors) {
                    this.errors = e.response.data.errors;
                }
            }
        },

        async getServices() {
            try {
                const res = await this.$server.get(
                    this.$page.props.route.services,
                    {
                        params: {
                            per_page: 500,
                            visibility: "public",
                        },
                    }
                );

                if (res.status == 200) {
                    this.services = res.data.data;
                }
            } catch (error) { }
        },

        async getBillingPeriod() {
            try {
                const res = await this.$server.get(
                    "/api/public/payments/billing-period"
                );

                if (res.status == 200) {
                    this.billing_periods = res.data.data.map((item) => ({
                        label: item.name,
                        value: item.name,
                    }));
                }
            } catch (error) { }
        },

        async getCurrencies() {
            try {
                const res = await this.$server.get(
                    "/api/public/payments/currencies"
                );

                if (res.status == 200) {
                    this.currencies = res.data.data.map((item) => ({
                        label: `${item.code} - ${item.name}`,
                        value: item.code,
                    }));
                }
            } catch (error) { }
        },

        async getServicesScope() {
            try {
                const res = await this.$server.get(this.service.links.scopes, {
                    params: {
                        per_page: 500,
                    },
                });

                if (res.status == 200) {
                    this.scopes = res.data.data;
                }
            } catch (error) { }
        },

        hasScope(id) {
            return this.form.scopes.includes(id);
        },

        toggleScope(id) {
            const index = this.form.scopes.indexOf(id);
            if (index === -1) {
                this.form.scopes.push(id);
            } else {
                this.form.scopes.splice(index, 1);
            }
        },
    },
};
</script>
