<template>
    <div class="q-pa-md q-gutter-sm">
        <q-btn round outline color="positive" @click="dialog = true" icon="mdi-plus-circle">
            <q-tooltip transition-show="rotate" transition-hide="rotate">
                Add new service
            </q-tooltip>
        </q-btn>

        <q-dialog v-model="dialog" persistent>
            <q-card class="card">
                <div class="card-main">
                    <q-card-section class="column items-center card-header">
                        <h6>Add new service</h6>
                    </q-card-section>

                    <q-separator />

                    <q-card-section class="column no-wrap q-gutter-y-md card-body">
                        <q-input v-model="form.name" label="Name" :error="!!errors.name">
                            <template v-slot:error>
                                <v-error :error="errors.name" />
                            </template>
                        </q-input>

                        <q-input v-model="form.description" label="Description" type="textarea"
                            :error="!!errors.description">
                            <template v-slot:error>
                                <v-error :error="errors.description" />
                            </template>
                        </q-input>

                        <q-select v-model="form.group_id" label="Group" :options="groups" option-label="name"
                            option-value="id" filter emit-value map-options :error="!!errors.group_id">
                            <template v-slot:error>
                                <v-error :error="errors.group_id" />
                            </template>
                        </q-select>
                        <q-item tag="label" v-ripple>
                            <q-item-section avatar>
                                <q-checkbox v-model="form.system" val="orange" color="orange" :error="!!errors.system">
                                    <template v-slot:error>
                                        <v-error :error="errors.system" />
                                    </template>
                                </q-checkbox>
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>System</q-item-label>
                                <q-item-label caption>
                                    This action cannot be undone.
                                </q-item-label>
                            </q-item-section>
                        </q-item>

                        <q-select v-model="form.visibility" :options="visibility" label="Visibility" />
                        <v-error :error="errors.visibility" />
                    </q-card-section>
                </div>

                <q-separator />
                <q-card-section class="row justify-between card-footer">
                    <q-btn outline color="positive" label="create" @click="create" />

                    <q-btn outline color="secondary" label="Cancel" @click="close" />
                </q-card-section>
            </q-card>
        </q-dialog>
    </div>
</template>
<script>
export default {
    emits: ["created"],

    data() {
        return {
            dialog: false,
            visibility: ["private", "public"],
            formGroup: {
                name: null,
                id: null,
            },
            form: {
                name: null,
                description: null,
                group_id: null,
                group_name: null,
                system: false,
                visibility: null,
            },
            errors: {},
            groups: [],
        };
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
            this.dialog = false;
        },

        open() {
            this.form = {};
            this.errors = {};
        },

        /**
         * Create a new client
         */
        async create() {
            try {
                const res = await this.$server.post(
                    this.$page.props.route.services,
                    this.form
                );

                if (res.status == 201) {
                    this.form = {};
                    this.errors = {};
                    this.$emit("created", true);
                    this.dialog = false;
                    this.$q.notify({
                        type: "positive",
                        message: "A new service has been created successfully",
                        timeout: 3000,
                    });
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.status == 422 &&
                    e.response.data.errors
                ) {
                    this.errors = e.response.data.errors;
                }

                if (
                    e.response &&
                    e.response.status != 422 &&
                    e.response.data &&
                    e.response.data.message
                ) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            }
        },

        getGroups() {
            this.$server
                .get(this.$page.props.route.groups, {
                    params: {
                        page: 1,
                        per_page: 1000,
                    },
                })
                .then((res) => {
                    this.groups = res.data.data;
                })
                .catch((e) => { });
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

.card-footer>.q-btn {
    padding: 0.4rem 2rem;
    border-radius: 0.6rem;
}
</style>
