<template>
    <q-btn
        round
        outline
        color="red"
        @click="dialog = true"
        icon="mdi-delete-outline"
    >
        <q-tooltip transition-show="rotate" transition-hide="rotate">
            Delete service
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
                <h6 class="text-gray-500">Delete service</h6>
            </q-card-section>
            <q-card-section>
                Are you share you want to remove this client with name
                <q-chip color="blue-darken-1">{{ item.name }}</q-chip> with ID
                <q-chip color="blue-darken-1">{{ item.id }}</q-chip> ?
            </q-card-section>

            <q-card-actions align="right">
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
    emits: ["deleted"],

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
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.$emit("deleted", true);
                    this.dialog = false;
                    this.$q.notify({
                        type: "positive",
                        message: "Service has been deleted successfully",
                        timeout: 3000,
                    });
                }
            } catch (err) {}
        },
    },
};
</script>
