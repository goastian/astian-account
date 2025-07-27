<template>
    <q-dialog v-model="dialog" persistent>
        <q-card class="q-pa-md full-width">
            <q-card-section>
                <div class="text-h6">Add new user</div>
            </q-card-section>

            <q-card-section>
                <div class="q-gutter-md">
                    <q-input outlined v-model="form.name" dense="dense" label="Name" :error="!!errors.name">
                        <template v-slot:error>
                            <v-error :error="errors.name"></v-error>
                        </template>
                    </q-input>
                    <q-input outlined v-model="form.last_name" dense="dense" label="Last name"
                        :error="!!errors.last_name">
                        <template v-slot:error>
                            <v-error :error="errors.last_name"></v-error>
                        </template>
                    </q-input>

                    <q-input outlined v-model="form.email" dense="dense" label="Email" :error="!!errors.email">
                        <template v-slot:error>
                            <v-error :error="errors.email"></v-error>
                        </template>
                    </q-input>

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

                    <q-input outlined v-model="form.phone" dense="dense" label="Phone" :error="!!errors.phone">
                        <template v-slot:error>
                            <v-error :error="errors.phone"></v-error>
                        </template>
                    </q-input>

                    <div class="w-full mb-2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Birthday
                        </label>
                        <VueDatePicker v-model="form.birthday" :enable-time-picker="false" :max-date="new Date()"
                            format="yyyy-MM-dd" model-type="format" placeholder="YYYY-MM-DD" />

                        <v-error :error="errors.birthday"></v-error>
                    </div>

                    <q-checkbox dense="dense" v-model="form.verify_email" label="Mark user email as verified"
                        :error="!!form.groups">
                        <template v-slot:error>
                            <v-error :error="errors.groups"></v-error>
                        </template>
                    </q-checkbox>
                </div>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn label="Save" icon="mdi-content-save-alert" color="primary" @click="create" />
                <q-btn label="Close" icon="mdi-close-circle" color="negative" @click="dialog = false" />
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-btn color="positive" outline round icon="mdi-plus" @click="open">
        <q-tooltip transition-show="rotate" transition-hide="rotate">
            Add new users
        </q-tooltip>
    </q-btn>
</template>

<script>
export default {
    emits: ["created"],
    data() {
        return {
            dialog: false,
            form: {},
            selected_groups: [],
            errors: {},
            countries: [],
            groups: [],
            formFields: {
                name: { label: "Name", type: "text" },
                last_name: { label: "Last Name", type: "text" },
                email: { label: "Email", type: "email" },
                phone: { label: "Phone Number", type: "text" },
            },
            filteredCountries: [],
            filteredDialCodes: [],
        };
    },

    watch: {
        selected_groups(value) {
            this.form.groups = value.map((item) => item.value);
        },
    },

    methods: {
        async open() {
            this.clean()
            this.dialog = true;
            await this.getCountries();
        },

        clean() {
            this.form.name = null;
            this.form.last_name = null;
            this.form.email = null;
            this.form.country = null;
            this.form.dial_code = null;
            this.form.phone = null;
            this.form.birthday = null;
            this.form.groups = [];
            this.form.verify_email = false;
            this.errors = {}
            this.dialog = false;
        },

        close(dialog) {
            dialog.value = false;
            this.form = { groups: [] };
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

        async create() {
            try {
                const res = await this.$server.post(
                    this.$page.props.route,
                    this.form
                );
                if (res.status === 201) {
                    this.clean()

                    this.$q.notify({
                        type: "positive",
                        message: "A new user has been created successfully",
                        timeout: 3000,
                    });

                    this.$emit("created", true);
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.data.errors &&
                    e.response.status == 422
                ) {
                    this.errors = e.response.data.errors;
                }

                if (
                    e.response &&
                    e.response.status != 422 &&
                    e.response.data &&
                    e.response.data.message
                ) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                    });
                }
            }
        },
        async getCountries() {
            try {
                const res = await this.$server.get("/api/public/countries", {
                    params: { order_by: "name_en", order_type: "asc" },
                });
                if (res.status === 200) this.countries = res.data;
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
