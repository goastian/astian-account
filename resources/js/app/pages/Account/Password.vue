<template>
    <v-user-layout>
        <q-page padding>
            <div class="q-pa-md q-gutter-md">
                <q-toolbar class="q-ma-sm">
                    <q-toolbar-title class="text-grey-7">
                        Update password
                    </q-toolbar-title>
                </q-toolbar>
                <div class="row q-col-gutter-md q-ma-sm">
                    <div class="col-xs-12">
                        <q-input filled dense v-model="form.current_password" label="Current password" type="password"
                            :error="!!errors.current_password" />
                        <v-error :error="errors.current_password" />
                    </div>

                    <div class="col-xs-12">
                        <q-input filled dense type="password" v-model="form.password" label="New password"
                            :error="!!errors.password" />
                        <v-error :error="errors.password" />
                    </div>

                    <div class="col-xs-12">
                        <q-input filled dense type="password" v-model="form.password_confirmation"
                            label="Confirm password" :error="!!errors.password_confirmation" />
                        <v-error :error="errors.password_confirmation" />
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
                current_password: "",
                password: "",
                password_confirmation: "",
            },
            errors: {},
        };
    },

    methods: {
        async update() {
            try {
                const res = await this.$server.put(
                    this.$page.props.user.links.change_password,
                    this.form
                );
                if (res.status === 200) {
                    this.form = {};
                    this.errors = {};
                    this.$q.notify({
                        type: "positive",
                        message: res.data.message,
                        timeout: 3000,
                    });
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>
