<template>
    <v-dialog fullscreen>
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                v-bind="activatorProps"
                color="blue-lighten-1"
                icon
                variant="tonal"
                @click="userRoles"
            >
                <v-icon icon="mdi-shield-crown"></v-icon>
            </v-btn>
        </template>

        <template v-slot:default="{ isActive }">
            <v-card>
                <v-card-title> Add or remove scopes </v-card-title>
                <v-card-text>
                    <v-scopes
                        :default_roles="user_roles"
                        @checked="checkedRoles"
                    ></v-scopes>
                </v-card-text>

                <v-card-actions>
                    <div class="flex justify-between w-100">
                        <v-btn
                            @click="add"
                            color="blue-darken-1"
                            prepend-icon="mdi-content-save-alert"
                            class="mx-4"
                            variant="flat"
                        >
                            add
                        </v-btn>
                        <v-btn
                            @click="close(isActive)"
                            prepend-icon="mdi-window-close"
                            variant="flat"
                            color="green-accent-2"
                        >
                            Close
                        </v-btn>
                        <v-btn
                            @click="revoke"
                            color="red-darken-1"
                            prepend-icon="mdi-delete-circle-outline"
                            class="mx-4"
                            variant="flat"
                        >
                            Revoke
                        </v-btn>
                    </div>
                </v-card-actions>
            </v-card>
        </template>
    </v-dialog>
</template>
<script>
export default {
    emits: ["updated"],
    props: ["item"],

    props: {
        item: {
            required: true,
            type: Object,
        },
    },

    data() {
        return {
            user_roles: [],
            form: {
                scopes: [],
                end_date: "",
            },
        };
    },

    methods: {
        /**
         *  reset keys when the windows is closed
         */
        close(isActive) {
            isActive.value = false;
        },

        async userRoles() {
            try {
                const res = await this.$server.get(this.item.links.scopes, {
                    params: { per_page: 150 },
                });

                if (res.status == 200) {
                    this.user_roles = res.data.data;
                }
            } catch (error) {
                console.log(error);
            }
        },

        checkedRoles(event) {
            this.form.scopes = event;
        },

        /**
         * update the user in the system
         *
         */
        async add() {
            try {
                const res = await this.$server.post(
                    this.item.links.scopes,
                    this.form,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );

                if (res.status == 201) {
                    this.errors = {};
                    this.userRoles();
                    this.$notification.success(res.data.message);
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

        async revoke() {
            try {
                const res = await this.$server.put(
                    this.item.links.scopes,
                    this.form,
                    {
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                    }
                );

                if (res.status == 201) {
                    this.errors = {};
                    this.userRoles();
                    this.$notification.success(res.data.message);
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

        /**
         * Get the all countries
         */
        async getCountries() {
            try {
                const res = await this.$server.get("/api/resources/countries");

                if (res.status == 200) {
                    this.countries = res.data;
                }
            } catch (e) {}
        },
    },
};
</script>
