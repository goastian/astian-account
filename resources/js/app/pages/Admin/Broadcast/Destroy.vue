<template>
    <q-dialog v-model="dialog" persistent>
        <q-card class="w-100">
            <q-card-section>
                <div class="text-h6">Delete channel</div>
            </q-card-section>

            <q-card-section>
                This channel will be removed. This action cannot be undone. Are
                you sure you want to proceed?
            </q-card-section>

            <q-card-actions align="right">
                <q-btn
                    label="Save"
                    icon="mdi-content-save-alert"
                    color="primary"
                    @click="destroy"
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
        flat
        color="red"
        icon="mdi-delete-circle-outline"
        @click="dialog = true"
    >
        <q-tooltip>Delete channel</q-tooltip>
    </q-btn>
</template>
<script>
export default {
    emits: ["deleted"],

    props: {
        item: {
            required: true,
            type: Object,
        },
    },

    data() {
        return {
            errors: {},
            dialog: false,
        };
    },

    methods: {
        /**
         * Create a new user in the system
         *
         */
        async destroy(isActive) {
            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.errors = {};
                    this.$emit("deleted", true);
                    this.$q.notify({
                        type: "positive",
                        message: "The channel has been deleted successfully",
                        timeout: 3000,
                    });
                }
            } catch (e) {
                if (e.response && e.response.data && e.response.data.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            } finally {
                isActive.value = false;
            }
        },
    },
};
</script>
