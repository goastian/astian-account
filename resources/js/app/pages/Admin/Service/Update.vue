<template>
    <q-btn round outline color="primary" @click="open(item)" icon="mdi-pencil">
        <q-tooltip transition-show="rotate" transition-hide="rotate">
            Update service
        </q-tooltip>
    </q-btn>

    <q-dialog v-model="dialog" persistent>
        <q-card class="card">
            <div class="card-main">
                <q-card-section class="column items-center card-header">
                    <h6>Detail of service</h6>
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

                    <q-select v-model="form.visibility" :options="visibility" label="Visibility" />
                    <v-error :error="errors.visibility" />
                </q-card-section>
            </div>

            <q-separator />
            <q-card-section class="flex justify-between card-footer">
                <q-btn outline color="positive" label="Update" @click="updateService" />

                <q-btn outline color="secondary" label="Cancel" @click="close" />
            </q-card-section>
        </q-card>
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
            visibility: ["private", "public"],
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
                );

                
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
