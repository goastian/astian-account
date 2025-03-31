<template>
    <button
        class="button"
        @click="dialog = true"
        icon="mdi-delete-outline"
    >
        <q-icon name="mdi-delete-outline" />
    </button>

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

            <q-card-actions align="right" class="bg-white text-teal">
                <q-btn
                    dense="dense"
                    color="primary"
                    label="Accept"
                    @click="destroy"
                />

                <q-btn
                    dense="dense"
                    caolor="secondary"
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
                    this.$q.notify({
                        type: "positive",
                        message: "Service deleted successfully",
                        timeout: 3000,
                    });
                    this.$emit("deleted", true);
                    this.dialog = false;
                }
            } catch (err) {
                this.$q.notify({
                    type: "negative",
                    message: err.response.data.message,
                    timeout: 3000,
                });
            }
        },
    },
};
</script>

<style scoped>

.button {
    padding: .4rem;
    cursor: pointer;
    outline: 1px solid #ccc;
    padding: .2rem .4rem;
    border-radius: 50%;
    transition: .2s ease-in-out;
}

.button:hover {
    background-color: #f50b0b;
    color: white;
    outline: none;
}

</style>
