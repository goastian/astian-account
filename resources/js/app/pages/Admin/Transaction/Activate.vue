<template>
    <div>
        <q-btn outline icon="mdi-power" color="positive" @click="dialog = true">
            <q-tooltip transition-show="rotate" transition-hide="rotate">
                Activate transaction
            </q-tooltip>
        </q-btn>
        <q-dialog v-model="dialog">
            <q-card>
                <q-card-section class="row items-center q-pb-none">
                    <div class="text-h6">Activate</div>
                    <q-space />
                    <q-btn icon="close" flat round dense v-close-popup />
                </q-card-section>

                <q-card-section>
                    Are you sure you want to activate this transaction?
                </q-card-section>
                <q-card-actions class="flex">
                    <q-btn
                        outline
                        label="Accept"
                        color="positive"
                        @click="activate"
                    />
                    <q-space></q-space>
                    <q-btn
                        outline
                        label="Cancel"
                        color="negative"
                        @click="dialog = false"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
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
            dialog: false,
        };
    },

    methods: {
        async activate() {
            try {
                const res = await this.$server.put(this.item.links.activate);

                if (res.status == 200) {
                    this.dialog = false;
                    this.$q.notify({
                        type: "positive",
                        message: "Transaction has been activated",
                    });
                    this.$emit("updated");
                }
            } catch (error) {
                if (error?.response?.status == 402) {
                    this.$q.notify({
                        type: "negative",
                        message: `This transaction can be activated : ${error.response.data.message}`,
                    });
                }
            } finally {
                this.dialog = false;
            }
        },
    },
};
</script>
