<template>
    <q-card class="q-pa-md">
        <q-card-section
            class="bg-primary text-white row items-center justify-between"
        >
            <div class="row items-center">
                <q-btn
                    flat
                    dense
                    round
                    icon="mdi-filter-multiple-outline"
                    class="q-mr-sm"
                    @click="show = !show"
                />
                <span class="text-subtitle1">Advanced Filters</span>
            </div>
            <q-btn flat dense round icon="mdi-broom" @click="clean" />
        </q-card-section>

        <q-slide-transition>
            <div v-show="show" class="q-pa-md">
                <div class="row q-col-gutter-md">
                    <!-- Searching -->
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <q-input
                            v-model="inputValue"
                            outlined
                            dense
                            label="Searching"
                            @keyup="emitFilterChange"
                            @input="emitFilterChange"
                        />
                    </div>

                    <!-- Searching by -->
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <q-select
                            v-model="selected_parameter"
                            :options="params"
                            option-value="value"
                            option-label="key"
                            outlined
                            dense
                            label="Searching by"
                            emit-value
                            map-options
                        />
                    </div>

                    <!-- Order by -->
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <q-select
                            v-model="order_by"
                            :options="params"
                            option-value="value"
                            option-label="key"
                            outlined
                            dense
                            label="Order by"
                            emit-value
                            map-options
                        />
                    </div>

                    <!-- Order type -->
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <q-select
                            v-model="order_type"
                            :options="orderTypes"
                            outlined
                            dense
                            label="Order type"
                            emit-value
                            map-options
                        />
                    </div>
                </div>
            </div>
        </q-slide-transition>
    </q-card>
</template>

<script>
export default {
    emits: ["change"],
    props: {
        params: {
            type: Array,
            required: true,
        },
    },

    watch: {
        selected_parameter() {
            this.emitFilterChange();
        },
        order_by() {
            this.emitFilterChange();
        },
        order_type() {
            this.emitFilterChange();
        },
    },
    data() {
        return {
            selected_parameter:
                this.params.length > 0 ? this.params[0].value : "",
            inputValue: "",
            show: false,
            order_by: "",
            order_type: "",
            orderTypes: [
                { label: "Ascending", value: "asc" },
                { label: "Descending", value: "desc" },
            ],
        };
    },
    methods: {
        clean() {
            this.inputValue = "";
            this.show = false;
            this.order_by = "";
            this.order_type = "";
            this.emitFilterChange();
        },

        emitFilterChange() {
            const filterObject = {};

            if (this.inputValue) {
                filterObject[this.selected_parameter] = this.inputValue;
            }

            if (this.order_by) {
                filterObject.order_by = this.order_by;
            }

            if (this.order_type) {
                filterObject.order_type = this.order_type;
            }

            this.$emit("change", filterObject);
        },
    },
};
</script>
