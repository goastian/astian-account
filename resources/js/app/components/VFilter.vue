<template>
    <div class="p-4 bg-white border rounded-lg shadow-md space-y-4">
        <div
            class="bg-blue-500 px-4 py-2 text-white font-semibold text-lg rounded-t-lg flex items-center justify-between"
        >
            <div>
                <v-btn
                    icon
                    variant="tonal"
                    class="me-2 text-white"
                    @click="show = !show"
                >
                    <v-icon icon="mdi-filter-multiple-outline"></v-icon>
                </v-btn>
                <span>Advanced Filters</span>
            </div>
            <v-btn icon variant="tonal" class="me-2 text-white" @click="clean">
                <v-icon icon="mdi-broom"></v-icon>
            </v-btn>
        </div>

        <div
            v-show="show"
            class="grid grid-cols-1 px-4 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4"
        >
            <!-- Searching -->
            <div class="w-full">
                <label
                    for="filterValue"
                    class="block text-sm font-semibold text-gray-700 mb-1"
                >
                    Searching
                </label>
                <input
                    id="filterValue"
                    type="text"
                    v-model="inputValue"
                    @input="emitFilterChange"
                    placeholder="Type your search..."
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-sm py-2 px-4"
                />
            </div>

            <!-- Searching by -->
            <div class="w-full">
                <label
                    for="parameter"
                    class="block text-sm font-semibold text-gray-700 mb-1"
                >
                    Searching by
                </label>
                <select
                    id="parameter"
                    v-model="selected_parameter"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-sm py-2 px-4"
                >
                    <option disabled value="">Select a parameter</option>
                    <option
                        v-for="(item, index) in params"
                        :key="index"
                        :value="item.value"
                    >
                        {{ item.key }}
                    </option>
                </select>
            </div>

            <!-- Order by -->
            <div class="w-full">
                <label
                    for="orderBy"
                    class="block text-sm font-semibold text-gray-700 mb-1"
                >
                    Order by
                </label>
                <select
                    id="orderBy"
                    v-model="order_by"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-sm py-2 px-4"
                >
                    <option disabled value="">Select an order field</option>
                    <option
                        v-for="(item, index) in params"
                        :key="index"
                        :value="item.value"
                    >
                        {{ item.key }}
                    </option>
                </select>
            </div>

            <!-- Order type -->
            <div class="w-full">
                <label
                    for="orderType"
                    class="block text-sm font-semibold text-gray-700 mb-1"
                >
                    Order type
                </label>
                <select
                    id="orderType"
                    v-model="order_type"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-sm py-2 px-4"
                >
                    <option disabled value="">Select order type</option>
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
            </div>
        </div>
    </div>
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
            selected_parameter: this.params[0] || "",
            inputValue: "",
            show: false,
            order_by: "",
            order_type: "",
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
