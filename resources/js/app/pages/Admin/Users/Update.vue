<template>
    <q-dialog v-model="dialog" persistent>
        <q-card class="q-pa-md full-width">
            <q-card-section>
                <div class="text-h6">Update User</div>
            </q-card-section>

            <q-card-section class="q-gutter-md">
                <div class="w-full mb-2">
                    <q-input v-model="form.name" label="Name" outlined dense :error="!!errors.name" />
                    <v-error :error="errors.name"></v-error>
                </div>
                <div class="w-full mb-2">
                    <q-input v-model="form.last_name" label="Last Name" outlined dense :error="!!errors.last_name" />
                    <v-error :error="errors.last_name"></v-error>
                </div>
                <div class="w-full mb-2">
                    <q-input v-model="form.email" label="Email" type="email" outlined dense :error="!!errors.email" />
                    <v-error :error="errors.email"></v-error>
                </div>

                <q-select v-model="form.country" dense outlined use-input fill-input hide-selected emit-value
                    map-options input-debounce="300" :options="filteredCountries" label="Country"
                    :error="!!errors.country" @filter="filterCountries">
                    <template v-slot:error>
                        <v-error :error="errors.country"></v-error>
                    </template>
                </q-select>

                <q-select v-model="form.dial_code" dense outlined use-input fill-input hide-selected emit-value
                    map-options input-debounce="300" :options="filteredDialCodes" label="Dial Code"
                    :error="!!errors.dial_code" @filter="filterDialCodes">
                    <template v-slot:error>
                        <v-error :error="errors.dial_code"></v-error>
                    </template>
                </q-select>

                <div class="w-full mb-2">
                    <q-input v-model="form.phone" label="Phone Number" outlined dense />
                    <v-error :error="errors.phone"></v-error>
                </div>

                <div class="w-full mb-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Birthday</label>
                    <VueDatePicker v-model="form.birthday" :enable-time-picker="false" :max-date="new Date()"
                        format="yyyy-MM-dd" />

                    <v-error :error="errors.birthday"></v-error>
                </div>

                <div class="w-full mb-2">
                    <q-input v-model="form.commission_rate" label="Commission rate" outlined dense
                        :error="!!errors.commission_rate" />
                    <v-error :error="errors.commission_rate"></v-error>
                </div>

                <div class="w-full mb-2">
                    <q-checkbox v-model="form.verify_email" label="Set user email as verified" dense
                        :error="!!errors.verify_email" />
                    <v-error :error="errors.verify_email"></v-error>
                </div>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Close" color="red" @click="dialog = false" />
                <q-btn flat label="Save" color="blue" @click="update" />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn icon="edit" outline round color="positive" @click="loadData(item)">
        <q-tooltip transition-show="rotate" transition-hide="rotate">
            Edit user
        </q-tooltip>
    </q-btn>
</template>

<script>
export default {
    emits: ["updated"],

    props: {
        item: {
            required: true,
            type: Object,
        },
    },

    data() {
        return {
            errors: {},
            form: {},
            countries: [],
            verify_email: false,
            birthday: null,
            dialog: false,
            filteredCountries: [],
            filteredDialCodes: [],
        };
    },

    methods: {
        /**
         *  reset keys when the windows is closed
         */
        close(isActive) {
            isActive.value = false;
            this.countries = [];
        },

        filterCountries(val, update) {
            if (!val) {
                update(() => {
                    this.filteredCountries = this.countries.map((c) => ({
                        label: `${c.emoji} ${c.name_en}`,
                        value: c.name_en,
                    }));
                });
                return;
            }

            const needle = val.toLowerCase();
            update(() => {
                this.filteredCountries = this.countries
                    .filter((c) => c.name_en.toLowerCase().includes(needle))
                    .map((c) => ({
                        label: `${c.emoji} ${c.name_en}`,
                        value: c.name_en,
                    }));
            });
        },

        filterDialCodes(val, update) {
            if (!val) {
                update(() => {
                    this.filteredDialCodes = this.countries.map((c) => ({
                        label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                        value: c.dial_code,
                    }));
                });
                return;
            }

            const needle = val.toLowerCase();
            update(() => {
                this.filteredDialCodes = this.countries
                    .filter((c) =>
                        `${c.name_en} ${c.dial_code}`
                            .toLowerCase()
                            .includes(needle)
                    )
                    .map((c) => ({
                        label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                        value: c.dial_code,
                    }));
            });
        },

        /**
         * Load necessary data to register new users
         */
        async loadData(item) {
            this.form = { ...item };
            await this.getCountries();
            this.dialog = true;
        },

        /**
         * update the user in the system
         *
         */
        async update() {
            try {
                const res = await this.$server.put(
                    this.item.links.update,
                    this.form
                );

                if (res.status == 200) {
                    this.errors = {};
                    this.$emit("updated", true);
                    this.$q.notify({
                        type: "positive",
                        message: "User has been updated successfully",
                        timeout: 3000,
                    });
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.data.errors &&
                    e.response.status == 422
                ) {
                    this.errors = e.response.data.errors;
                } //

                if (
                    e.response &&
                    e.response.status != 422 &&
                    e.response.data &&
                    e.response.data.message
                ) {
                    this.$q.notify({
                        type: "positive",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            }
        },

        /**
         * Get the all countries
         */
        async getCountries() {
            try {
                const res = await this.$server.get("/api/public/countries", {
                    params: {
                        order_by: "name_en",
                        order_type: "asc",
                    },
                });

                if (res.status == 200) {
                    this.countries = res.data;
                }
            } catch (e) { }

            this.filteredCountries = this.countries.map((c) => ({
                label: `${c.emoji} ${c.name_en}`,
                value: c.name_en,
            }));
            this.filteredDialCodes = this.countries.map((c) => ({
                label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                value: c.dial_code,
            }));
        },
    },
};
</script>
