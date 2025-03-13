<template>
    <q-btn round flat color="primary" @click="open(item)" icon="mdi-pencil" />

    <q-dialog
        v-model="dialog"
        persistent
        position="right"
        maximized
    >
        <div class="containerDialog">
                <q-card
                    class="card"
                >
                    <div class="card-main">
                        <q-card-section class="column items-center card-header">
                            <q-icon
                                name="mdi-update"
                                size="4rem"
                                color="secondary"
                            />
                            <h6>Update a scope and assign a service and role to it</h6>
                            <span>Define the details and set up its assignment to properly integrate it into the system.</span>
                        </q-card-section>

                        <q-separator />

                        <q-card-section class="column no-wrap q-gutter-y-md card-body">
                            <q-select
                                v-model="service"
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
                                v-model="role"
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

                            <q-checkbox
                                v-model="form.public"
                                label="Public"
                                color="orange"
                                :error="!!errors.confidential"
                            >
                                <template v-slot:error>
                                    <v-error :error="errors.description"></v-error>
                                </template>
                            </q-checkbox>
                            <q-checkbox
                                v-model="form.active"
                                label="Active"
                                color="orange"
                                :error="!!errors.confidential"
                            >
                                <template v-slot:error>
                                    <v-error :error="errors.description"></v-error>
                                </template>
                            </q-checkbox>
                        </q-card-section>
                    </div>

                    <q-separator/>
                    <q-card-section class="row justify-between card-footer">
                        <q-btn
                            dense="dense"
                            color="secondary"
                            label="Update Scope"
                            @click="updateScope"
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
                description: "",
            },
            form: {},
            rol: {},
            service: {
                id: null,
                name: null
            },
            role: {
                id: null,
                name: null,
            },
            dialog: false,
            servicess: [],
            servicesScope: [],
            roless: [],
            rolesScope: [],
            publi: null,
            active: null,
        };
    },

    created() {
        this.getServices();
        this.getRoles();
    },

    methods: {
        close() {
            this.form = {};
            this.errors = {};
            this.dialog = false;
        },

        open(item) {
            const { service_slug, service_id, role_id, role_slug, ...form } = item;
            this.service.id = service_id;
            this.service.name = service_slug;
            this.role.id = role_id;
            this.role.name = role_slug;

            this.form = form;

            this.errors = {};
            this.dialog = true;
        },

        async updateScope() {
            this.form.service_id = this.service.id;
            this.form.role_id = this.role.id;
            const a = this.form.active;
            const p = this.form.public;
            this.form.active = a == true ? 1 : 0;
            this.form.public = p == true ? 1 : 0;
            try {
                const res = await this.$server.put(
                    this.form.links.update,
                    this.form,
                    {
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                    }
                );

                console.log(res)
                if (res.status == 200) {
                    this.$emit("updated", true);
                    this.errors = {};
                    this.dialog = false;
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        },

        getServices() {
            this.$server.get("/api/admin/services", {
                params: {
                    "page": 1,
                    "per_page": 1000
                }
            })
                .then((res) => {
                    this.servicesScope = res.data.data;
                    this.servicess = res.data.data;
                })
                .catch((e) => {});
        },

        filterService(val, update) {
            if(!val) {
                update(() => this.servicesScope = this.servicess);
                return;
            }

            update(() => {
                const needle = val.toLowerCase();
                const filtered = this.servicess.filter((option) => {
                    return option.name.toLowerCase().includes(needle)
                });
                this.servicesScope = filtered;
            })
        },

        getRoles() {
            this.$server.get("/api/admin/roles", {
                params: {
                    "page": 1,
                    "per_page": 1000
                }
            })
                .then((res) => {
                    this.rolesScope = res.data.data;
                    this.roless = res.data.data;
                })
                .catch((e) => {});
        },

        filterRoles(val, update) {
            if(!val) {
                update(() => this.rolessScope = this.roless);
                return;
            }

            update(() => {
                const needle = val.toLowerCase();
                const filtered = this.roless.filter((option) => {
                    return option.name.toLowerCase().includes(needle)
                });
                this.rolesScope = filtered;
            })
        }
    },
};
</script>

<style scoped>
.containerDialog {
    width: auto;
    height: 100%;
    padding: .5rem;
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
    padding: .4rem 2rem;
    border-radius: .6rem;
}
</style>
