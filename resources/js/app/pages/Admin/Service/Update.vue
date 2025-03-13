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
                        </q-card-section>
                    </div>

                    <q-separator/>
                    <q-card-section class="row justify-between card-footer">
                        <q-btn
                            dense="dense"
                            color="secondary"
                            label="Update Service"
                            @click="updateService"
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
            dialog: false,
        };
    },

    methods: {
        close() {
            this.form = {};
            this.errors = {};
            this.dialog = false;
        },

        open(item) {
            const { system, ...form } = item;
            this.form = form;
            this.errors = {};
            this.dialog = true;
        },

        async updateService() {
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
