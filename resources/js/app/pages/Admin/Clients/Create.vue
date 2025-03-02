<template>
    <v-dialog max-width="800">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                v-bind="activatorProps"
                color="blue-lighten-1"
                icon
                variant="tonal"
                @click="open"
            >
                <v-icon icon="mdi-plus"></v-icon>
            </v-btn>
        </template>

        <template v-slot:default="{ isActive }">
            <v-card>
                <v-card-title> Add new client </v-card-title>
                <v-card-text>
                    <v-row>
                        <!-- Name -->
                        <v-col sm="12" md="8">
                            <v-text-field
                                v-model="form.name"
                                label="Name"
                                variant="solo"
                            >
                                <template #details>
                                    <v-error :error="errors.name"></v-error>
                                </template>
                            </v-text-field>
                        </v-col>
                        <v-col sm="12" md="4">
                            <v-checkbox
                                label="Confidential"
                                v-model="form.confidential"
                            >
                                <template #details>
                                    <v-error
                                        :error="errors.confidential"
                                    ></v-error>
                                </template>
                            </v-checkbox>
                        </v-col>
                        <v-col sm="12">
                            <v-text-field
                                v-model="form.redirect"
                                label="Redirect URL"
                                variant="solo"
                            >
                                <template #details>
                                    <v-error :error="errors.redirect"></v-error>
                                </template>
                            </v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        @click="create(isActive)"
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
                redirect: null,
                confidential: false,
            },
            errors: {},
        };
    },

    methods: {
        /**
         * Clean the form when it is closed
         */
        close(isActive) {
            this.client = {};
            this.errors = {};
            isActive.value = false;
        },

        open() {
            this.form = {};
            this.errors = {};
        },

        /**
         * Create a new client
         */
        async create(isActive) {
            try {
                const res = await this.$server.post(
                    "/api/admin/clients",
                    this.form
                );

                if (res.status == 201) {
                    this.form = {};
                    this.errors = {};
                    this.$emit("created", true);
                    isActive.value = false;
                }
            } catch (e) {
                if (e.response && e.response.data.errors) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>
