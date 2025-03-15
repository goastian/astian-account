<template>
    <div class="q-pa-md q-gutter-sm">
        <q-btn
            round
            dense="dense"
            color="primary"
            @click="open"
            icon="mdi-plus-circle"
        />

        <q-dialog v-model="dialog" persistent position="right" maximized>
            <div class="containerDialog">
                <q-card class="card">
                    <div class="card-main">
                        <q-card-section class="column items-center card-header">
                            <q-icon
                                name="mdi-plus"
                                size="4rem"
                                color="secondary"
                            />
                            <h6>
                                Create a scope and assign a service and role to
                                it
                            </h6>
                            <span
                                >Creating a scope assigns specific services and
                                roles, controlling access to features based on
                                user permissions.</span
                            >
                        </q-card-section>

                        <q-separator />

                        <q-card-section
                            class="column no-wrap q-gutter-y-md card-body"
                        >
                            <q-select
                                v-model="formService"
                                label="Services"
                                :options="servicesScope"
                                option-label="name"
                                option-value="id"
                                use-input
                                filter
                                @filter="filterService"
                                stack-label
                                filled
                            >
                                <template v-slot:prepend>
                                    <q-icon name="mdi-home" />
                                </template>

                                <template v-slot:no-option>
                                    <q-item>
                                        <q-item-section class="text-grey">
                                            No Results
                                        </q-item-section>
                                    </q-item>
                                </template>

                                <template v-slot:after>
                                    <q-icon name="mdi-asterisk" size="1rem" />
                                </template>
                            </q-select>

                            <q-select
                                v-model="formRoles"
                                label="Roles"
                                :options="rolesScope"
                                option-label="name"
                                option-value="id"
                                use-input
                                filter
                                @filter="filterRoles"
                                stack-label
                                filled
                            >
                                <template v-slot:prepend>
                                    <q-icon name="mdi-home" />
                                </template>

                                <template v-slot:no-option>
                                    <q-item>
                                        <q-item-section class="text-grey">
                                            No Results
                                        </q-item-section>
                                    </q-item>
                                </template>

                                <template v-slot:after>
                                    <q-icon name="mdi-asterisk" size="1rem" />
                                </template>
                            </q-select>
                        </q-card-section>
                    </div>

                    <q-separator />
                    <q-card-section class="row justify-between card-footer">
                        <q-btn
                            dense="dense"
                            color="secondary"
                            label="Create New Service"
                            @click="create"
                            no-caps
                            class="btn-create"
                        />

                        <q-btn
                            dense="dense"
                            color="secondary"
                            label="Cancel"
                            @click="close"
                            outline
                            no-caps
                            class="btn-cancel"
                        />
                    </q-card-section>
                </q-card>
            </div>
        </q-dialog>
    </div>
</template>
<script>
export default {
    emits: ["created"],

    data() {
        return {
            dialog: false,
            formGroup: {
                name: null,
                id: null,
            },
            formRoles: {
                name: null,
                id: null,
            },
            form: {
                service_id: null,
                role_id: null,
            },
            formService: {},
            system: false,
            errors: {},
            services: [],
            servicesScope: [],
            roles: [],
            rolesScope: [],
        };
    },

    watch: {
        system(value) {
            this.form.system = value ? 1 : 0;
        },
    },

    methods: {
        /**
         * Clean the form when it is closed
         */
        close() {
            this.services = {};
            this.errors = {};
            this.system = false;
            this.dialog = false;
        },

        open() {
            this.dialog = true;
            this.getServices();
            this.getRoles();

            this.form = {};
            this.system = false;
            this.errors = {};
        },

        /**
         * Create a new client
         */
        async create() {
            try {
                this.form.service_id = this.formService.id;
                this.form.role_id = this.formRoles.id;
                const res = await this.$server.post(
                    "/api/admin/scopes",
                    this.form,
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
                    this.dialog = false;
                }
            } catch (e) {
                if (e.response && e.response.data.errors) {
                    this.errors = e.response.data.errors;
                }
            }
        },

        getServices() {
            this.$server
                .get("/api/admin/services", {
                    params: {
                        page: 1,
                        per_page: 1000,
                    },
                })
                .then((res) => {
                    this.servicesScope = res.data.data;
                    this.services = res.data.data;
                })
                .catch((e) => {});
        },

        filterService(val, update) {
            if (!val) {
                update(() => (this.servicesScope = this.services));
                return;
            }

            update(() => {
                const needle = val.toLowerCase();
                const filtered = this.services.filter((option) => {
                    return option.name.toLowerCase().includes(needle);
                });
                this.servicesScope = filtered;
            });
        },

        getRoles() {
            this.$server
                .get("/api/admin/roles", {
                    params: {
                        page: 1,
                        per_page: 1000,
                    },
                })
                .then((res) => {
                    this.rolesScope = res.data.data;
                    this.roles = res.data.data;
                })
                .catch((e) => {});
        },

        filterRoles(val, update) {
            if (!val) {
                update(() => (this.rolessScope = this.roles));
                return;
            }

            update(() => {
                const needle = val.toLowerCase();
                const filtered = this.roles.filter((option) => {
                    return option.name.toLowerCase().includes(needle);
                });
                this.rolesScope = filtered;
            });
        },
    },
};
</script>

<style scoped>
.containerDialog {
    width: auto;
    height: 100%;
    padding: 0.5rem;
}

.card {
    width: 500px;
    max-width: 90vw;
    height: 100%;
    border-radius: 1rem;
    display: flex;
    flex-direction: column;
}

.card-main {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.card-header {
    flex-shrink: 0;
    padding: 2rem 3rem;
    text-align: center;
}

.card-body {
    padding: 2rem;
    flex-grow: 1;
}

.card-footer {
    flex-shrink: 0;
}

.card-footer > .q-btn {
    padding: 0.4rem 2rem;
    border-radius: 0.6rem;
}
</style>
