<template>
    <q-dialog v-model="dialog" persistent>
        <q-card>
            <q-card-section>
                <div class="text-h6">Personal access client name</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
                <q-input v-model="form.name" autofocus />
                <v-error :error="errors.name"></v-error>
            </q-card-section>

            <q-card-actions align="right" class="text-primary">
                <q-btn outline label="Close" @click="dialog = false" />
                <q-btn
                    outline
                    label="Create"
                    @click="createPersonalAccessClient"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-btn outline color="primary" icon="mdi-account-key-outline" @click="open">
        Personal client
        <q-tooltip transition-show="scale" transition-hide="scale">
            This client allows you to generate API keys and authenticate your
            apps securely.
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
            errors: [],
        };
    },

    methods: {
        open() {
            this.form.name = null;
            this.dialog = true;
            this.errors = [];
        },

        async createPersonalAccessClient() {
            try {
                const res = await this.$server.post(
                    this.$page.props.route["personal"],
                    this.form
                );

                if (res.status == 201) {
                    this.$q.notify({
                        type: "positive",
                        message: res.data.message,
                        timeout: 5000,
                    });
                    this.$emit("created");
                    this.dialog = false;
                }
            } catch (err) {
                if (err?.response?.status == 422) {
                    this.errors = err.response.data.errors;
                }
            }
        },
    },
};
</script>
