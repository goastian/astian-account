<template>
    <q-dialog v-model="dialog" full-width>
        <template v-slot:default>
            <q-card>
                <q-card-section class="row items-center">
                    <div class="text-h6">Add or remove scopes</div>
                    <q-space />
                    <q-btn flat icon="close" @click="dialog = false" />
                </q-card-section>

                <q-card-section>
                    <v-scopes
                        :default_roles="user_roles"
                        @checked="checkedRoles"
                    ></v-scopes>
                </q-card-section>

                <q-card-actions align="between">
                    <q-btn
                        @click="add"
                        color="primary"
                        icon="save"
                        class="q-mx-sm"
                    >
                        Add
                    </q-btn>
                    <q-btn @click="dialog = false" color="green" icon="close">
                        Close
                    </q-btn>
                    <q-btn
                        @click="revoke"
                        color="red"
                        icon="delete"
                        class="q-mx-sm"
                    >
                        Revoke
                    </q-btn>
                </q-card-actions>
            </q-card>
        </template>
    </q-dialog>

    <q-btn color="blue" icon="mdi-shield-crown" round flat @click="openDialog" />
</template>

<script>
export default {
    props: {
        item: {
            required: true,
            type: Object,
        },
    },

    data() {
        return {
            dialog: false,
            user_roles: [],
            form: {
                scopes: [],
                end_date: "",
            },
        };
    },

    methods: {
        openDialog() {
            this.dialog = true;
            this.userRoles();
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
                console.error(error);
            }
        },

        checkedRoles(event) {
            this.form.scopes = event;
        },

        async add() {
            try {
                const res = await this.$server.post(
                    this.item.links.scopes,
                    this.form,
                    {
                        headers: { "Content-Type": "multipart/form-data" },
                    }
                );

                if (res.status == 201) {
                    this.errors = {};
                    this.userRoles();
                    this.$q.notify({
                        type: "positive",
                        message: res.data.message,
                    });
                }
            } catch (e) {
                if (e.response?.status == 422) {
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
                    this.$q.notify({
                        type: "positive",
                        message: res.data.message,
                    });
                }
            } catch (e) {
                if (e.response?.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>
