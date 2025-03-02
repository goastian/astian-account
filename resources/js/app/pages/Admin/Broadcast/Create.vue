<template>
    <v-dialog>
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                v-bind="activatorProps"
                color="blue-lighten-1"
                icon
                variant="tonal"
            >
                <v-icon icon="mdi-plus"></v-icon>
            </v-btn>
        </template>

        <template v-slot:default="{ isActive }">
            <v-card>
                <v-card-title> Add new channel </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col sm="12" class="py-1">
                            <v-text-field
                                density="compact"
                                v-model="form.name"
                                label="Name"
                                variant="solo"
                                class="w-full"
                            >
                                <template #details>
                                    <v-error :error="errors.name"></v-error>
                                </template>
                            </v-text-field>
                        </v-col>

                        <v-col sm="12" class="py-1">
                            <v-textarea
                                density="compact"
                                v-model="form.description"
                                label="Last name"
                                variant="solo"
                            >
                                <template #details>
                                    <v-error
                                        :error="errors.description"
                                    ></v-error>
                                </template>
                            </v-textarea>
                        </v-col>

                        <v-col sm="12">
                            <v-chip color="red-accent-2">
                                Be careful, if you mark this option, this action
                                cannot be undone.
                            </v-chip>
                            <v-checkbox
                                density="compact"
                                v-model="form.system"
                                label="System"
                                variant="solo"
                            ></v-checkbox>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        @click="create"
                        color="blue-darken-1"
                        prepend-icon="mdi-content-save-alert"
                        class="mx-4"
                        variant="flat"
                    >
                        Save
                    </v-btn>
                    <v-btn
                        @click="close(isActive)"
                        prepend-icon="mdi-close-circle"
                        variant="flat"
                        color="red-lighten-1"
                    >
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </template>
    </v-dialog>
</template>
<script>
export default {
    emits: ["created"],

    data() {
        return {
            form: {
                name: null,
                description: null,
                system: false,
            },
            errors: {},
        };
    },

    methods: {
        /**
         *  reset keys when the windows is closed
         */
        close(isActive) {
            isActive.value = false;
            this.form = [];
        },

        /**
         * Create a new user in the system
         *
         */
        async create() {
            try {
                const form = new FormData();
                form.append("name", this.form.name);
                form.append("description", this.form.description);
                form.append("system", this.form.system ? 1 : 0);

                const res = await this.$server.post(
                    "/api/admin/broadcasts",
                    form,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );

                if (res.status == 201) {
                    this.form = {};
                    this.errors = {};
                    this.$emit("created", true);
                    this.$notification.success(
                        "A new channel has been created"
                    );
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.data.errors &&
                    e.response.status == 422
                ) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>
