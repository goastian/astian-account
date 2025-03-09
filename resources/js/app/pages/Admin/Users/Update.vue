<template>
    <q-dialog v-model="dialog" persistent>
        <q-card class="q-pa-md w-100">
            <q-card-section>
                <div class="text-h6">Update User</div>
            </q-card-section>

            <q-card-section class="q-gutter-md">
                <!-- Name -->
                <q-input
                    v-model="item.name"
                    label="Name"
                    outlined
                    dense
                    :error="!!errors.name"
                />
                <q-input
                    v-model="item.last_name"
                    label="Last Name"
                    outlined
                    dense
                    :error="!!errors.last_name"
                />
                <q-input
                    v-model="item.email"
                    label="Email"
                    type="email"
                    outlined
                    dense
                    :error="!!errors.email"
                />

                <!-- Country -->
                <q-select
                    v-model="item.country"
                    label="Country"
                    outlined
                    dense
                    :options="
                        countries.map((c) => ({
                            label: `${c.emoji} ${c.name_en}`,
                            value: c.name_en,
                        }))
                    "
                    emit-value
                    map-options
                    :error="!!errors.country"
                />

                <!-- Dial Code -->
                <q-select
                    v-model="item.dial_code"
                    label="Dial Code"
                    outlined
                    dense
                    :options="
                        countries.map((c) => ({
                            label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                            value: c.dial_code,
                        }))
                    "
                    emit-value
                    map-options
                    :error="!!errors.dial_code"
                />

                <!-- Phone -->
                <q-input
                    v-model="item.phone"
                    label="Phone Number"
                    outlined
                    dense
                    :error="!!errors.phone"
                />

                <!-- Birthday -->
                <div class="w-full mb-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2"
                        >Birthday</label
                    >
                    <VueDatePicker
                        v-model="item.birthday"
                        :enable-time-picker="false"
                        :max-date="new Date()"
                        format="yyyy-MM-dd"
                    />

                    <v-error :error="errors.birthday"></v-error>
                </div>

                <!-- Email Verified Checkbox -->
                <q-checkbox
                    v-model="verify_email"
                    label="Mark user email as verified"
                    dense
                />
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Close" color="red" @click="dialog = false" />
                <q-btn flat label="Save" color="blue" @click="update" />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn icon="edit" color="primary" round flat dense @click="loadData(item)" />
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
            countries: [],
            verify_email: false,
            birthday: null,
            dialog: false,
        };
    },

    watch: {
        verify_email(value) {
            this.item.verify_email = value ? 1 : 0;
        },
    },

    methods: {
        /**
         *  reset keys when the windows is closed
         */
        close(isActive) {
            isActive.value = false;
            this.countries = [];
        },

        /**
         * Load necessary data to register new users
         */
        async loadData(item) {
            this.verify_email = item.verify_email;
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
                    this.item,
                    {
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                    }
                );

                if (res.status == 200) {
                    this.form = { scope: [] };
                    this.errors = {};
                    this.$emit("updated", true);
                    this.$notification.success(
                        "User has been updated successfully"
                    );
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
                    this.$notification.error(e.response.data.message);
                }
            }
        },

        /**
         * Get the all countries
         */
        async getCountries() {
            try {
                const res = await this.$server.get("/api/resources/countries", {
                    params: {
                        order_by: "name_en",
                        order_type: "asc",
                    },
                });

                if (res.status == 200) {
                    this.countries = res.data;
                }
            } catch (e) {}
        },
    },
};
</script>
