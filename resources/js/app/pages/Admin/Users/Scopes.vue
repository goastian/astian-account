<template>
    <q-dialog v-model="dialog" full-screen persistent>
        <template v-slot:default>
            <q-card>
                <q-card-section class="row items-center">
                    <div class="text-h6">Add scopes</div>
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
                </q-card-actions>
            </q-card>
        </template>
    </q-dialog>
    <q-btn
        outline
        round
        icon="mdi-shield-plus-outline"
        color="positive"
        @click="openDialog"
    >
        <q-tooltip transition-show="rotate" transition-hide="rotate">
            Add scopes
        </q-tooltip>
    </q-btn>
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
            user_roles: [],
            dialog: false,
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
            } catch (error) {}
        },

        checkedRoles(event) {
            this.form.scopes = event;
        },

        async add() {
            try {
                const res = await this.$server.post(
                    this.item.links.scopes,
                    this.form
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
