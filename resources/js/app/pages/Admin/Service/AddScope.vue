<template>
    <button class="button" @click="open">
        <q-icon name="mdi-plus" />
    </button>

    <q-dialog
        v-model="dialog"
        persistent
        maximized
    >
        <div class="containerDialog">
            <q-card
                class="card"
            >
                <div class="card-main">
                    <q-card-section class="column items-center card-header">
                        <q-icon
                            name="mdi-plus"
                            size="4rem"
                            color="secondary"
                        />
                        <h6>Create a Service and assign it to group</h6>
                        <span>Define the details and set up its assignment to properly integrate it into the system.</span>
                    </q-card-section>

                    <q-separator />

                    <q-card-section class="column no-wrap q-gutter-y-md card-body">
                        <q-select
                            v-model="formscopes"
                            label="Scopes"
                            :options="scopesLocal"
                            option-label="name"
                            option-value="id"
                            use-input
                            filter
                            @filter="filterScopes"
                            stack-label
                            filled
                            use-chips
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
                        <div class="row">
                            <q-checkbox
                                v-model="active"
                                label="Active"
                                color="orange"
                                :error="!!errors.confidential"
                            >
                                <template v-slot:error>
                                    <v-error :error="errors.description"></v-error>
                                </template>
                            </q-checkbox>

                            <q-checkbox
                                v-model="publi"
                                label="Public"
                                color="orange"
                                :error="!!errors.confidential"
                            >
                                <template v-slot:error>
                                    <v-error :error="errors.description"></v-error>
                                </template>
                            </q-checkbox>

                            <q-checkbox
                                v-model="api"
                                label="Api Key"
                                color="orange"
                                :error="!!errors.confidential"
                            >
                                <template v-slot:error>
                                    <v-error :error="errors.description"></v-error>
                                </template>
                            </q-checkbox>
                        </div>
                    </q-card-section>
                </div>

                <q-separator/>
                <q-card-section class="row justify-between card-footer">
                    <q-btn
                        dense="dense"
                        color="secondary"
                        label="Add role"
                        @click="addScope"
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
    emits: ["add"],

    props: {
        id: {
            type: String,
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
            dialog: false,
            scopes: [],
            scopesLocal: [],
            formscopes: null,
            active: false,
            publi: false,
            api: false
        }
    },

    methods: {
        close() {
            this.formscopes = null;
            this.errors = {};
            this.dialog = false;
            this.active = false;
            this.publi = false;
            this.api = false;
        },

        open() {
            this.errors = {};
            this.dialog = true;
            this.$server('/api/admin/roles')
                .then((res) => {
                    this.scopesLocal = res.data.data;
                    this.scopes = res.data.data;
                })
                .catch((e) => {})
        },

        addScope() {
            this.$server.post(`/api/admin/services/${this.id}/scopes`, {
                'role_id': this.formscopes.id,
                'active': this.active,
                'public': this.publi,
                'api_key': this.api
            }, {
                headers: {
                    "Content-Type": "multipart/form-data"
                }
            }).then((res) => {
                if(res.status == 201) {
                    this.formscopes = null;
                    this.scopesLocal = [];
                    this.scopes = [];
                    this.active = false;
                    this.publi = false;
                    this.api = false;
                    this.errors = {};
                    this.$emit("add", true);
                    this.dialog = false;
                    this.$q.notify({
                        type: "positive",
                        message: "Role added to the service",
                        timeout: 3000,
                    });
                }
            }).catch((e) => { })
        },

        filterScopes(val, update) {
            if(!val) {
                update(() => this.scopesLocal = this.scopes);
                return;
            }

            update(() => {
                const needle = val.toLowerCase();
                const filtered = this.scopes.filter((option) => {
                    return option.name.toLowerCase().includes(needle)
                });
                this.scopesLocal = filtered;
            })
        }
    }
}
</script>

<style scoped>

.button {
    cursor: pointer;
}

.containerDialog {
    width: auto;
    height: auto;
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
