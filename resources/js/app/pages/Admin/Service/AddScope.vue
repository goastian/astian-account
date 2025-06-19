<template>
    <q-btn outline round color="positive" @click="open" :icon="icon">
        <q-tooltip transition-show="rotate" transition-hide="rotate">
            {{ scope ? "Update scope" : "Add new role" }}
        </q-tooltip>
    </q-btn>

    <q-dialog v-model="dialog">
        <q-card>
            <q-card-section>
                <div class="text-h6">Add new scope</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
                <q-select v-model="form.role_id" label="Roles" :options="roles" option-label="name" option-value="id"
                    filter emit-value map-options :error="!!errors.role_id">
                    <template v-slot:error>
                        <v-error :error="errors.role_id" />
                    </template>
                </q-select>

                <q-item tag="label" v-ripple>
                    <q-item-section avatar>
                        <q-checkbox v-model="form.api_key" val="orange" color="orange" :error="!!errors.api_key">
                            <template v-slot:error>
                                <v-error :error="errors.api_key" />
                            </template>
                        </q-checkbox>
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>API KEY</q-item-label>
                        <q-item-label caption>
                            Available for API key function
                        </q-item-label>
                    </q-item-section>
                </q-item>

                <q-item tag="label" v-ripple>
                    <q-item-section avatar>
                        <q-checkbox v-model="form.active" val="orange" color="orange" :error="!!errors.active">
                            <template v-slot:error>
                                <v-error :error="errors.active" />
                            </template>
                        </q-checkbox>
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>Active</q-item-label>
                        <q-item-label caption>
                            Available to be used
                        </q-item-label>
                    </q-item-section>
                </q-item>

                <q-item tag="label" v-ripple>
                    <q-item-section avatar>
                        <q-checkbox v-model="form.public" val="orange" color="orange" :error="!!errors.public">
                            <template v-slot:error>
                                <v-error :error="errors.public" />
                            </template>
                        </q-checkbox>
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>Public</q-item-label>
                        <q-item-label caption>
                            Available to all users if true
                        </q-item-label>
                    </q-item-section>
                </q-item>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn outline :label="scope ? 'Update' : 'Add'" color="primary" @click="addScopes" />
                <q-btn outline label="Close" color="negative" @click="dialog = false" />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>
<script>
export default {
    emits: ["created"],

    props: {
        icon: {
            required: false,
            type: String,
            default: "mdi-lock-open-plus-outline",
        },
        scope: {
            required: false,
            type: Object,
        },
        link: {
            required: true,
            type: String,
        },
    },

    data() {
        return {
            dialog: false,
            roles: [],
            form: {
                api_key: false,
                active: false,
                public: false,
                role_id: "",
            },
            errors: {},
        };
    },

    methods: {
        async open() {
            await this.getRoles();
            if (this.scope) {
                this.form = { ...this.scope };
                this.form.role_id = this.scope.role.id;
            }
            this.dialog = true;
        },

        async getRoles() {
            try {
                const res = await this.$server.get(
                    this.$page.props.route.roles,
                    {
                        params: {
                            per_page: 100,
                        },
                    }
                );

                if (res.status == 200) {
                    this.roles = res.data.data;
                }
            } catch (error) { }
        },

        async addScopes() {
            try {
                const res = await this.$server.post(this.link, this.form);

                if (res.status == 201) {
                    this.roles = res.data.data;
                    this.$emit("created");
                    this.$q.notify({
                        type: "positive",
                        message: "Scopes updated successfully",
                        timeout: 3000,
                    });
                    this.dialog = false;
                }
            } catch (error) {
                if (
                    error.response &&
                    error.response.status == 422 &&
                    error.response.data.errors
                ) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>
