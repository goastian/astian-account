<template>
    <div class="q-pa-md q-gutter-sm">
        <q-btn
            round
            dense="dense"
            color="primary"
            @click="open"
            icon="mdi-plus-circle"
        />

        <q-dialog
            v-model="dialog"
            persistent
            transition-show="scale"
            transition-hide="scale"
        >
            <q-card class="q-pa-md full-width">
                <q-card-section class="text-center">
                    <h6 class="text-gray-500">Add new client</h6>
                </q-card-section>
                <q-card-section>
                    <q-input
                        v-model="form.name"
                        label="Name"
                        dense="dense"
                        :error="!!errors.name"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.name"></v-error>
                        </template>
                    </q-input>

                    <q-input
                        v-model="form.redirect"
                        label="Redirect"
                        dense="dense"
                        :error="!!errors.redirect"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.redirect"></v-error>
                        </template>
                    </q-input>

                    <q-checkbox
                        v-model="form.confidential"
                        label="Confidential client"
                        color="orange"
                        :error="!!errors.confidential"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.redirect"></v-error>
                        </template>
                    </q-checkbox>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn
                        dense="dense"
                        color="primary"
                        label="Accept"
                        @click="create"
                    />

                    <q-btn
                        dense="dense"
                        color="secondary"
                        label="Close"
                        @click="close"
                    />
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
            this.client = {};
            this.errors = {}; 
            this.dialog = false;
        },

        open() {
            this.form.name = null;
            this.form.redirect = null;
            this.form.confidential = false; 
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
                if (e.response && e.response.data.errors) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>
