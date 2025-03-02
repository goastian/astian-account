<template>
    <v-dialog max-width="800">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                v-bind="activatorProps"
                color="blue-lighten-1"
                icon
                variant="tonal"
                @click="open(item)"
            >
                <v-icon icon="mdi-plus"></v-icon>
            </v-btn>
        </template>

        <template v-slot:default="{ isActive }">
            <v-card>
                <v-card-title> Update new client </v-card-title>
                <v-card-text>
                    <v-row>
                        <!-- Name -->
                        <v-col sm="12" class="py-1">
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
                        <v-col sm="12" class="py-1">
                            <v-text-field
                                v-model="form.redirect"
                                label="Name"
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
    emits: ["updated"],

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            errors: {
                name: "",
                redirect: "",
            },
            form: {},
        };
    },

    methods: {
        close(isActive) {
            this.form = {};
            this.errors = {};
            isActive.value = false;
        },

        open(item) {
            this.form = item;
        },

        async updateClient(isActive) {
            try {
                const res = await this.$server.put(
                    this.form.links.update,
                    this.form
                );

                if (res.status == 200) {
                    this.$emit("updated", true);
                    this.errors = {};
                    isActive.value = false;
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>
