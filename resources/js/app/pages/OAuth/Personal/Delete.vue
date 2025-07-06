<template>
    <q-dialog v-model="dialog">
        <q-card>
            <q-card-section class="row items-center">
                <q-icon name="mdi-alert-circle" color="red" size="md" />
                <span class="q-ml-sm"
                    >Are you sure you want to remove this token with name</span
                >
                <q-chip color="blue-6" text-color="white" class="q-ml-sm">
                    {{ item.name }}
                </q-chip>
                ?
            </q-card-section>

            <q-card-actions align="right">
                <q-btn
                    label="Close"
                    color="negative"
                    flat
                    @click="dialog = false"
                />
                <q-btn label="Agree" color="primary" @click="destroy" />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn
        color="negative"
        icon="mdi-delete-outline"
        round
        flat
        @click="dialog = true"
    />
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
                if (res.status === 200) {
                    this.$emit("deleted", true);
                    this.dialog = false;
                }
            } catch (e) {}
        },
    },
};
</script>
