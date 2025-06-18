<template>
    <q-btn round outline color="positive" @click="open(item)" icon="mdi-pencil">
        <q-tooltip transition-show="rotate" transition-hide="rotate">
            Update group
        </q-tooltip>
    </q-btn>

    <q-dialog v-model="dialog" persistent transition-show="scale" transition-hide="scale">
        <q-card class="q-pa-md full-width">
            <q-card-section class="text-center">
                <h6 class="text-gray-500">Update group</h6>
            </q-card-section>
            <q-card-section>
                <q-input v-model="form.description" label="Description" dense="dense" :error="!!errors.redirect"
                    type="textarea">
                    <template v-slot:error>
                        <v-error :error="errors.redirect"></v-error>
                    </template>
                </q-input>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn outline color="positive" label="Accept" @click="updateClient" />

                <q-btn outline color="secondary" label="Close" @click="close" />
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
                description: "",
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

                if (res.status == 201) {
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
