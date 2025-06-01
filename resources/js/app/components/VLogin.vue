<template>
    <q-dialog v-model="dialog" persistent>
        <q-card
            class="q-pa-lg q-rounded-xl shadow-10 relative-position"
            style="width: 100%; max-width: 400px"
        >
            <q-btn
                icon="close"
                round
                dense
                flat
                class="absolute-top-right q-mt-sm q-mr-sm z-top"
                @click="close"
            />

            <q-card-section class="text-center q-mt-md">
                <div class="text-h5 text-weight-bold text-grey-8">
                    {{ $page.props.app_name }}
                </div>
            </q-card-section>

            <q-card-section>
                <q-input
                    v-model="form.email"
                    type="email"
                    label="Email"
                    outlined
                    dense
                    class="q-mb-md"
                    :error="!!errors.email"
                    :error-message="errors.email"
                />

                <q-input
                    v-model="form.password"
                    type="password"
                    label="Password"
                    outlined
                    dense
                    class="q-mb-md"
                    :error="!!errors.password"
                    :error-message="errors.password"
                />

                <div class="text-right q-mb-md">
                    <q-btn
                        flat
                        label="Forgot your password?"
                        class="text-blue"
                        @click="
                            open($page.props.auth_routes['forgot_password'])
                        "
                    />
                </div>

                <q-btn
                    label="Sign in"
                    color="primary"
                    class="full-width q-mt-md"
                    outline
                    @click="login"
                />

                <div
                    v-if="$page.props.allow_register"
                    class="text-center text-sm text-grey-7 q-mt-md"
                >
                    Don't have an account?
                    <q-btn
                        flat
                        label="Sign up."
                        class="text-blue"
                        @click="open($page.props.auth_routes['register'])"
                    />
                </div>
            </q-card-section>
        </q-card>
    </q-dialog>
</template>

<script>
export default {
    emits: ["close"],

    props: {
        guest: {
            required: false,
            type: Boolean,
            default: false,
        },
        refer: {
            type: String,
            required: false,
        }
    },

    data() {
        return {
            form: {
                email: null,
                password: null,
            },
            errors: {
                email: null,
                password: null,
            },
            dialog: false,
        };
    },

    watch: {
        guest(value) {
            this.dialog = value;
        },
    },

    methods: {
        async login() {
            try {
                const res = await this.$server.post(
                    this.$page.props.auth_routes["login"],
                    this.form
                );
                if (res.status == 200) {
                    this.$q.notify({
                        type: "positive",
                        message: res.data.data.message,
                        timeout: 3000,
                    });

                    window.location.reload();
                    this.dialog = false;
                }
            } catch (error) {}
        },

        open(uri) {
            const url = `${uri}?referral_code=${this.refer}`;
            window.location.href = url;
        },

        close() {
            this.$emit("close", false);
        },
    },
};
</script>
