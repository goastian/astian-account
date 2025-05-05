<template>
    <q-btn
        round
        outline
        color="negative"
        @click="dialog = true"
        icon="mdi-delete-outline"
    >
        <q-tooltip transition-show="rotate" transition-hide="rotate">
            Revoke Scope
        </q-tooltip>
    </q-btn>

    <q-dialog
        v-model="dialog"
        persistent
        transition-show="scale"
        transition-hide="scale"
    >
        <q-card class="w-100 py-4">
            <q-card-section class="text-center">
                <h6 class="text-gray-500">Revoke plan</h6>
            </q-card-section>
            <q-card-section>
                Are you share you want to remove this scope
            </q-card-section>

            <q-card-actions align="right" class="bg-white text-teal">
                <q-btn
                    outline
                    color="positive"
                    label="Accept"
                    @click="destroy"
                />

                <q-btn
                    outline
                    color="secondary"
                    label="Close"
                    @click="dialog = false"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>
<script>
export default {
    emits: ["revoked"],

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
        async destroy() {
            try {
                const res = await this.$server.delete(this.item.links.revoke);

                if (res.status == 200) {
                    this.$emit("revoked", true);
                    this.dialog = false;
                    this.$q.notify({
                        type: "positive",
                        message: "Scope has been revoked successfully",
                        timeout: 3000,
                    });
                }
            } catch (err) {}
        },
    },
};
</script>
