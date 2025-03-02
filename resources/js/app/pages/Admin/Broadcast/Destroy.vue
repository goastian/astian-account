<template>
    <v-dialog max-width="400">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                v-bind="activatorProps"
                color="red-accent-2"
                icon
                variant="tonal"
            >
                <v-icon icon="mdi-delete-circle-outline"></v-icon>
            </v-btn>
        </template>

        <template v-slot:default="{ isActive }">
            <v-card>
                <v-card-title>Delete channel </v-card-title>
                <v-card-text>
                    This channel will be removed. This action cannot be undone.
                    Are you sure you want to proceed?
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        @click="destroy(isActive)"
                        color="blue-darken-1"
                        prepend-icon="mdi-content-save-alert"
                        class="mx-4"
                        variant="flat"
                    >
                        proceed
                    </v-btn>
                    <v-btn
                        @click="close(isActive)"
                        prepend-icon="mdi-close-circle"
                        variant="flat"
                        color="red-lighten-1"
                    >
                        Cancel
                    </v-btn>
                </v-card-actions>
            </v-card>
        </template>
    </v-dialog>
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
        };
    },

    methods: {
        /**
         *  reset keys when the windows is closed
         */
        close(isActive) {
            isActive.value = false;
        },

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
                    this.$notification.success(
                        "The channel has been deleted successfully"
                    );
                }
            } catch (e) {
                if (e.response && e.response.data && e.response.data.message) {
                    this.$notification.success(e.response.data.message);
                }
            } finally {
                isActive.value = false;
            }
        },
    },
};
</script>
