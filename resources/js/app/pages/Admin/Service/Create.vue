<template>
    <div class="q-pa-md q-gutter-sm">
        <q-btn
            round
            dense="dense"
            color="primary"
            @click="dialog = true"
            icon="mdi-plus-circle"
        />

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
                                name="mdi-plus"
                                size="4rem"
                                color="secondary"
                            />
                            <h6>Create a Service and assign it to group</h6>
                            <span>Define the details and set up its assignment to properly integrate it into the system.</span>
                        </q-card-section>

                        <q-separator />

                        <q-card-section class="column no-wrap q-gutter-y-md card-body">
                            <q-input
                                v-model="form.name"
                                label="Name"
                                stack-label
                                filled
                            >
                                <template v-slot:prepend>
                                    <q-icon name="mdi-account-badge-outline" />
                                </template>
                                <template v-slot:after>
                                    <q-icon name="mdi-asterisk" size="1rem" />
                                </template>
                            </q-input>

                            <q-input
                                v-model="form.description"
                                label="Description"
                                stack-label
                                filled
                                type="textarea"
                            >
                                <template v-slot:prepend>
                                    <q-icon name="mdi-home" />
                                </template>

                                <template v-slot:after>
                                    <q-icon size="1rem"/>
                                </template>
                            </q-input>

                            <q-select
                                v-model="formGroup"
                                label="Group"
                                :options="groupsSerice"
                                option-label="name"
                                option-value="id"
                                use-input
                                filter
                                @filter="filterGroup"
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
                                v-model="system"
                                :label="system ? 'This action is irreversible. Are you sure?' : 'System'"
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
            form: {
                name: null,
                description: null,
                group_id: null,
                group_name: null,
                system: 0,
            },
            system: false,
            errors: {},
            groupss: [],
            groupsSerice: [],
        };
    },

    watch: {
        system(value) {
            this.form.system = value ? 1 : 0;
        },
    },

    created() {
        this.getGroups();
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
            this.form = {};
            this.system = false;
            this.errors = {};
        },

        /**
         * Create a new client
         */
        async create() {
            try {
                this.form.group_id = this.formGroup.id;
                this.form.group_name = this.formGroup.group_name;
                const res = await this.$server.post(
                    "/api/admin/services",
                    this.form,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data"
                        },
                    },
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

        getGroups() {
            this.$server.get("/api/admin/groups", {
                params: {
                    "page": 1,
                    "per_page": 1000
                }
            })
                .then((res) => {
                    this.groupsSerice = res.data.data;
                    this.groupss = res.data.data;
                })
                .catch((e) => {});
        },

        filterGroup(val, update) {
            if(!val) {
                update(() => this.groupsSerice = this.groupss);
                return;
            }

            update(() => {
                const needle = val.toLowerCase();
                const filtered = this.groupss.filter((option) => {
                    return option.name.toLowerCase().includes(needle)
                });
                this.groupsSerice = filtered;
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
