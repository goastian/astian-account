<template>
    <div class="q-pa-md q-gutter-sm">
        <q-btn round outline color="positive" @click="open" icon="mdi-plus-circle">
            <q-tooltip transition-show="rotate" transition-hide="rotate">
                Add new role
            </q-tooltip>
        </q-btn>

        <q-dialog v-model="dialog" persistent transition-show="scale" transition-hide="scale">
            <q-card class="q-pa-md full-width">
                <q-card-section class="text-center">
                    <h6 class="text-gray-500">Add new role</h6>
                </q-card-section>
                <q-card-section>
                    <q-input v-model="form.name" label="Name" outline :error="!!errors.name">
                        <template v-slot:error>
                            <v-error :error="errors.name"></v-error>
                        </template>
                    </q-input>

                    <q-input v-model="form.description" label="Description" outline :error="!!errors.description"
                        type="textarea">
                        <template v-slot:error>
                            <v-error :error="errors.description" />
                        </template>
                    </q-input>

                    <q-item tag="label" v-ripple>
                        <q-item-section avatar>
                            <q-checkbox v-model="form.system" val="orange" color="orange" :error="!!errors.system">
                                <template v-slot:error>
                                    <v-error :error="errors.system" />
                                </template>
                            </q-checkbox>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>System</q-item-label>
                            <q-item-label caption>
                                This action cannot be undone.
                            </q-item-label>
                        </q-item-section>
                    </q-item>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn outline color="positive" label="Accept" @click="create" />

                    <q-btn outline color="secondary" label="Close" @click="close" />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>
<script>
export default {
    emits: ["created"],

    data() {
        return {
            dialog: false,
            form: {},
            errors: {},
        };
    },

    methods: {
        /**
         * Clean the form when it is closed
         */
        close() {
            this.roles = {};
            this.errors = {};
            this.dialog = false;
        },

        open() {
            this.form.name = null;
            this.form.description = null;
            this.form.system = false;
            this.errors = {};
            this.dialog = true;
        },

        /**
         * Create a new client
         */
        async create() {
            try {
                const res = await this.$server.post(
                    this.$page.props.route,
                    this.form
                );

                if (res.status == 201) {
                    this.form = {};
                    this.errors = {};
                    this.$emit("created", true);
                    this.dialog = false;
                }
            } catch (e) {
                if (e.response?.data?.errors && e.response?.status == 422) {
                    this.errors = e.response.data.errors;
                }
                if (e.response?.status == 400 && e.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }

                if (e.response?.status == 403 && e.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            }
        },
    },
};
</script>
