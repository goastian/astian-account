<template>
    <div class="q-pa-md q-gutter-sm">
        <q-btn
            round
            dense="dense"
            color="primary"
            @click="dialog = true"
            icon="mdi-plus-circle"
        />

        <q-dialog
            v-model="dialog"
            persistent
            transition-show="scale"
            transition-hide="scale"
        >
            <q-card class="w-100 py-4">
                <q-card-section class="text-center">
                    <h6 class="text-gray-500">Add new group</h6>
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
                        v-model="form.description"
                        label="Description"
                        dense="dense"
                        :error="!!errors.name"
                        type="textarea"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.name" />
                        </template>
                    </q-input>

                    <q-checkbox
                        v-model="system"
                        :label="system ? 'This action is irreversible. Are you sure?' : 'System'"
                        color="orange"
                        :error="!!errors.confidential"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.description"></v-error>
                        </template>
                    </q-checkbox>
                </q-card-section>

                <q-card-actions align="right" class="bg-white text-teal">
                    <q-btn
                        dense="dense"
                        color="primary"
                        label="Accept"
                        @click="create"
                    />

                    <q-btn
                        dense="dense"
                        caolor="secondary"
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
            form: {
                name: null,
                description: null,
                system: 0,
            },
            system: false,
            errors: {},
        };
    },

    watch: {
        system(value) {
            this.form.system = value ? 1 : 0;
        },
    },

    methods: {
        /**
         * Clean the form when it is closed
         */
        close() {
            this.group = {};
            this.errors = {};
            this.system = false;
            this.dialog = false;
        },

        open() {
            this.form = {};
            this.system = false;
            this.errors = {};
        },

        /**
         * Create a new client
         */
        async create() {
            try {
                const res = await this.$server.post(
                    "/api/admin/groups",
                    this.form,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data"
                        },
                    },
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
