<template>
    <div>
        <q-btn
            outline
            label="Cancel payment"
            icon="mdi-cart-remove"
            color="negative"
            size="sm"
            class="q-mt-sm full-width"
            @click="dialog = true"
        />
        <q-dialog v-model="dialog">
            <q-card>
                <q-card-section class="row items-center q-pb-none">
                    <div class="text-h6">Cancel operation</div>
                    <q-space />
                    <q-btn icon="close" flat round dense v-close-popup />
                </q-card-section>

                <q-card-section>
                    Are you sure you want to cancel the process to payment?
                </q-card-section>
                <q-card-actions class="flex">
                    <q-btn
                        outline
                        label="Accept"
                        color="positive"
                        @click="cancel"
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
    emits: ["success"],

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
        async cancel() {
            try {
                const res = await this.$server.put(this.item.links.cancel);

                if (res.status == 200) {
                    this.$emit("success");
                }
            } catch (error) {
            } finally {
                this.dialog = false;
            }
        },
    },
};
</script>
