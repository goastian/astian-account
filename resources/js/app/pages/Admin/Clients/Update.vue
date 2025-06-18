<template>
    <q-btn round outline color="positive" @click="open(item)" icon="mdi-pencil">
        <q-tooltip transition-show="rotate" transition-hide="rotate">
            Update client
        </q-tooltip>
    </q-btn>

    <q-dialog
        v-model="dialog"
        persistent
        transition-show="scale"
        transition-hide="scale"
    >
        <q-card class="q-pa-md full-width">
            <q-card-section class="text-center">
                <h6 class="text-gray-500">Update client</h6>
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
            </q-card-section>

            <q-card-actions align="right">
                <q-btn
                    dense="dense"
                    color="primary"
                    label="Accept"
                    @click="updateClient"
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
</template>
<script>
export default {
    emits: ["updated"],

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            errors: {
                name: "",
                redirect: "",
            },
            form: {},
            dialog: false,
        };
    },

    methods: {
        close() {
            this.form = {};
            this.errors = {};
            this.dialog = false;
        },

        open(item) {
            this.form = { ...item };
            this.errors = {};
            this.dialog = true;
        },

        async updateClient() {
            try {
                const res = await this.$server.put(
                    this.form.links.update,
                    this.form
                );

                if (res.status == 200) {
                    this.$emit("updated", true);
                    this.errors = {};
                    this.dialog = false;
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
