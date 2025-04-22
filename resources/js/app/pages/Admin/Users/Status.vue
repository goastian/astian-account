<template>
    <q-dialog v-model="dialog" persistent>
        <q-card>
            <q-card-section class="row items-center">
                <span class="q-ml-sm"
                    >You are currently not connected to any network.</span
                >
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Cancel" color="primary" v-close-popup />
                <q-btn
                    flat
                    label="Accept"
                    @click="action(item)"
                    color="primary"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn
        :color="item.disabled ? 'negative' : 'positive'"
        outline
        round
        icon="mdi-power"
        @click="open"
    >
        <q-tooltip transition-show="rotate" transition-hide="rotate">
            {{ item.disabled ? "Enable this user" : "Disable this user" }}
        </q-tooltip>
    </q-btn>
</template>
<script>
export default {
    props: ["item"],

    emits: ["updated"],

    data() {
        return {
            dialog: false,
        };
    },

    methods: {
        open() {
            this.dialog = true;
        },

        action(item) {
            if (item.disabled) {
                this.enable();
            } else {
                this.disable();
            }
        },

        async disable() {
            try {
                const res = await this.$server.delete(this.item.links.disable);
                if (res.status === 200) {
                    this.$q.notify({
                        type: "positive",
                        message: "User disabled successfully",
                        timeout: 3000,
                    });

                    this.$emit("updated", true);
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.status != 422 &&
                    e.response.data &&
                    e.response.data.message
                ) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                    });
                }
            } finally {
                this.dialog = false;
            }
        },

        async enable() {
            try {
                const res = await this.$server.get(this.item.links.enable);
                if (res.status === 200) {
                    this.$q.notify({
                        type: "positive",
                        message: "User enabled successfully",
                        timeout: 3000,
                    });

                    this.$emit("updated", true);
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.status != 422 &&
                    e.response.data &&
                    e.response.data.message
                ) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                    });
                }
            } finally {
                this.dialog = false;
            }
        },
    },
};
</script>
