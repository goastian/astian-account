<template>
    <v-user-layout>
        <q-page padding>
            <div class="q-pa-md q-gutter-md">
                <q-toolbar class="q-ma-sm">
                    <q-toolbar-title class="text-grey-7">
                        Account information
                    </q-toolbar-title>
                </q-toolbar>
                <div class="row q-col-gutter-md q-ma-sm">
                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <q-input filled dense v-model="form.name" label="First Name" hint="Maximum 100 characters"
                            counter maxlength="100" :error="!!errors.name" />
                        <v-error :error="errors.name" />
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <q-input filled dense v-model="form.last_name" label="Last Name" hint="Maximum 100 characters"
                            counter maxlength="100" :error="!!errors.last_name" />
                        <v-error :error="errors.last_name" />
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <q-input filled dense v-model="form.email" label="Email" type="email"
                            hint="Must be unique and valid. Max 100 characters" maxlength="100"
                            :error="!!errors.email" />
                        <v-error :error="errors.email" />
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <q-select v-model="form.country" dense="dense" emit-value map-options :options="countries.map((c) => ({
                            label: `${c.emoji} ${c.name_en} `,
                            value: c.name_en,
                        }))
                            " label="Country" outlined :error="!!errors.country">
                        </q-select>
                        <v-error :error="errors.country"></v-error>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <q-input filled dense v-model="form.city" label="City" hint="Optional. Max 100 characters"
                            maxlength="100" :error="!!errors.city" />
                        <v-error :error="errors.city" />
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <q-input filled dense v-model="form.address" label="Address" hint="Optional. Max 150 characters"
                            maxlength="150" :error="!!errors.address" />
                        <v-error :error="errors.address" />
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <q-select v-model="form.dial_code" dense="dense" emit-value map-options :options="countries.map((c) => ({
                            label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                            value: c.dial_code,
                        }))
                            " label="Dial Code" outlined :error="!!errors.dial_code">
                        </q-select>
                        <v-error :error="errors.dial_code"></v-error>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <q-input filled dense v-model="form.phone" label="Phone"
                            hint="Required if dial code is filled. Must be unique. Max 25 characters" maxlength="25"
                            :error="!!errors.phone" />
                        <v-error :error="errors.phone" />
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <div class="q-mb-xs text-subtitle2">Birthday</div>
                        <VueDatePicker v-model="form.birthday" :enable-time-picker="false" :max-date="new Date()"
                            format="yyyy-MM-dd" model-type="format" placeholder="YYYY-MM-DD" />
                        <div class="text-caption text-grey q-mt-xs">
                            Optional. Format: YYYY-MM-DD. Must be a past date
                        </div>
                        <v-error :error="errors.birthday" />
                    </div>
                </div>

                <div class="q-mt-lg">
                    <q-btn label="Submit" color="primary" unelevated no-caps @click="update" />
                </div>
            </div>
        </q-page>
    </v-user-layout>
</template>

<script>
export default {
    data() {
        return {
            form: {
                name: "",
                last_name: "",
                email: "",
                country: "",
                city: "",
                address: "",
                dial_code: "",
                phone: "",
                birthday: "",
            },
            countries: [],
            errors: {},
        };
    },

    created() {
        this.getCountries();
    },

    mounted() {
        this.form = this.$page.props.user;
    },

    methods: {
        async update() {
            try {
                const res = await this.$server.put(
                    this.form.links.update,
                    this.form
                );
                if (res.status === 200) {
                    this.form = res.data.data;
                    this.errors = {};
                    this.$q.notify({
                        type: "positive",
                        message: "Information has been updated successfully",
                        timeout: 3000,
                    });
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        },

        async getCountries() {
            try {
                const res = await this.$server.get("/api/public/countries", {
                    params: { order_by: "name_en", order_type: "asc" },
                });
                if (res.status === 200) {
                    this.countries = res.data;
                }
            } catch (e) { }
        },
    },
};
</script>
