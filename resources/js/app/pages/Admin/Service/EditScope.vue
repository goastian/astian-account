<template>
    <button class="button" @click="open">
        <q-icon name="mdi-pencil" />
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
                        label="Update role"
                        @click="updateRole"
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
    emits: ["edit"],

    props: {
        item: {
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
            active: false,
            publi: false,
            api: false
        }
    },

    methods: {
        close() {
            this.errors = {};
            this.form = {};
            this.dialog = false;
            this.active = false;
            this.publi = false;
            this.api = false;
        },

        open() {
            const { active, api_key } = this.item;
            this.active = active;
            this.publi = this.item.public;
            this.api = this.item.api_key;
            this.errors = {};
            this.dialog = true;
        },

        async updateRole() {
            console.log(this.item)
            try {
                const res = await this.$server.post(
                    this.item.links.assign,
                    {
                        "role_id": this.item.role.id,
                        "active" : this.active,
                        "public" : this.publi,
                        "api_key": this.api
                    },
                    {
                        headers: {
                            "Content-Type": "application/multipart/form-data",
                        },
                    }
                );

                console.log(res)
                if (res.status == 201) {
                    this.$emit("edit", true);
                    this.errors = {};
                    this.dialog = false;
                    this.active = false;
                    this.publi = false;
                    this.api = false;
                    this.$q.notify({
                        type: "positive",
                        message: "Service edited successfully",
                        timeout: 3000,
                    });
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        }
    },
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
