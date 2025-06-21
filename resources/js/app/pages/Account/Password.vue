<template>
    <v-user-layout>
        <q-page padding>
            <div class="q-pa-md q-gutter-md">
                <q-toolbar class="q-ma-sm">
                    <q-toolbar-title class="text-grey-7">
                        Update password
                    </q-toolbar-title>
                </q-toolbar>
                <div class="row form items-start">
                    <div class="col-8 row card form-left">
                        <div class="col-xs-12">
                            <q-input
                                filled
                                dense
                                v-model="form.current_password"
                                label="Current password"
                                type="password"
                                :error="!!errors.current_password"
                            />
                            <v-error :error="errors.current_password" />
                        </div>

                        <div class="col-xs-12">
                            <q-input
                                filled
                                dense
                                type="password"
                                v-model="form.password"
                                label="New password"
                                :error="!!errors.password"
                            />
                            <v-error :error="errors.password" />
                        </div>

                        <div class="col-xs-12">
                            <q-input
                                filled
                                dense
                                type="password"
                                v-model="form.password_confirmation"
                                label="Confirm password"
                                :error="!!errors.password_confirmation"
                            />
                            <v-error :error="errors.password_confirmation" />
                        </div>
                        <div class="">
                            <q-btn
                                label="Submit"
                                color="primary"
                                unelevated
                                no-caps
                                @click="update"
                            />
                        </div>
                    </div>
                    <div class="col column card tips">
                        <div class="column q-gutter-y-md">
                            <h3 class="subtitle">Security Tips</h3>
                            <div
                                class="row no-wrap q-gutter-x-md"
                                v-for="(item, index) in tips"
                                :key="index"
                            >
                                <span class="icon green">
                                    {{ item.icon }}
                                </span>
                                <span>
                                    {{ item.description }}
                                </span>
                            </div>
                        </div>
                    </div>
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
            tips: [
                {
                    icon: '✓',
                    description: 'Use unique passwords for each account.'
                },
                {
                    icon: '✓',
                    description: 'Include uppercase letters, numbers, and symbols.'
                },
                {
                    icon: '✓',
                    description: 'Avoid using personal information.'
                },
                {
                    icon: '✓',
                    description: 'Change your passwords every 3 months.'
                },
            ],
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

<style scoped>
.form {
    gap: 2rem;
}

.card {
    background-color: var(--q-background-primary);
    border-radius: .5rem;
    padding: 2rem 1.8rem;
}

.form-left {
    gap: .8rem;
}

.subtitle {
    font-size: 20px;
    font-weight: 700;
    line-height: 22px;
    color: var(--q-color);
}

.icon.green {
    color: var(--q-color-green);
}


@media (max-width: 700px) {
    .form {
        flex-direction: column-reverse;
    }

    .form-left {
        width: 100%;
    }

    .tips {
        width: 100%;
    }
}
</style>
