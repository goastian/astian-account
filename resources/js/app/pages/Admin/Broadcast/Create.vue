<template>
    <q-dialog v-model="dialog" persistent>
        <q-card class="w-100">
            <q-card-section>
                <div class="text-h6">Add new channel</div>
            </q-card-section>

            <q-card-section>
                <div class="q-gutter-md">
                    <q-input
                        outlined
                        v-model="form.name"
                        dense="dense"
                        label="Name"
                        :error="!!errors.name"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.name"></v-error>
                        </template>
                    </q-input>
                    <q-input
                        outlined
                        v-model="form.description"
                        dense="dense"
                        label="description"
                        :error="!!errors.description"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.description"></v-error>
                        </template>
                    </q-input>

                    <q-checkbox
                        v-model="system"
                        label="Be careful, if you mark this option, this action  cannot be undone."
                        color="orange"
                        :error="!!errors.system"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.redirect"></v-error>
                        </template>
                    </q-checkbox>
                </div>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn
                    label="Save"
                    icon="mdi-content-save-alert"
                    color="primary"
                    @click="create"
                />
                <q-btn
                    label="Close"
                    icon="mdi-close-circle"
                    color="negative"
                    @click="dialog = false"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-btn
        class="glossy"
        round
        color="secondary"
        icon="mdi-plus"
        @click="dialog = true"
    >
        <q-tooltip> Add new channel</q-tooltip>
    </q-btn>
</template>
<script>
export default {
    emits: ["created"],

    data() {
        return {
            form: {
                name: null,
                description: null,
                system: 0,
            },
            errors: {},
            dialog: false,
            system: false,
        };
    },
    watch: {
        system(value) {
            this.form.system = value ? 1 : 0;
        },
    },

    methods: {
        /**
         *  reset keys when the windows is closed
         */
        close() {
            this.dialog = false;
            this.form = [];
        },

        /**
         * Create a new user in the system
         *
         */
        async create() {
            try {
                const res = await this.$server.post(
                    "/api/admin/broadcasts",
                    this.form,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );

                if (res.status == 201) {
                    this.form = {};
                    this.errors = {};
                    this.$emit("created", true);
                    this.$q.notify({
                        type: "positive",
                        message: "A new channel has been created",
                        timeout: 3000,
                    });
                    this.dialog = false;
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.data.errors &&
                    e.response.status == 422
                ) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>
