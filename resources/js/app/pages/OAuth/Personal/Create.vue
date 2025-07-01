<template>
    <q-btn outline round icon="mdi-plus" color="positive" @click="open" />

    <q-dialog v-model="dialog" maximized>
        <q-card>
            <q-card-section>
                <q-toolbar>
                    <q-toolbar-title>Generate a new API KEY</q-toolbar-title>
                    <q-btn flat dense icon="mdi-close" @click="close" />
                </q-toolbar>
            </q-card-section>

            <q-card-section>
                <q-input
                    v-model="form.name"
                    label="Name"
                    outlined
                    :error="!!errors.name"
                >
                    <template v-slot:error>
                        <v-error :error="errors.name"></v-error>
                    </template>
                </q-input>

                <div class="q-mt-md">
                    <q-btn
                        @click="create"
                        color="primary"
                        icon="mdi-content-save-alert"
                        label="Create"
                        outline
                    />
                </div>

                <div
                    v-if="token"
                    @click="copyToClipboard(token.accessToken)"
                    class="q-mt-md q-pa-md bg-green-4 text-white cursor-pointer"
                >
                    <q-icon name="mdi-content-copy" size="md" />
                    <span class="q-ml-sm"
                        >Please copy this token and save it in a secure
                        place</span
                    >
                </div>
            </q-card-section>

            <q-card-section>
                <div
                    v-for="(services, group) in groupedScopes"
                    :key="group"
                    class="q-mb-md"
                >
                    <q-expansion-item
                        expand-separator
                        :label="group"
                        v-model="expanded"
                        header-class="text-primary"
                    >
                        <div
                            v-for="(roles, service) in services"
                            :key="service"
                            class="q-pa-md bg-grey-2 rounded-borders"
                        >
                            <h6 class="text-weight-medium text-grey-7">
                                {{ service }}
                            </h6>
                            <q-option-group
                                v-model="form.scopes"
                                :options="
                                    roles.map((role) => ({
                                        label: role.name,
                                        value: role.id,
                                    }))
                                "
                                color="primary"
                                type="checkbox"
                                class="checkbox-grid"
                            />
                        </div>
                    </q-expansion-item>
                </div>
            </q-card-section>
        </q-card>
    </q-dialog>
</template>

<script>
export default {
    emits: ["created"],
    data() {
        return {
            form: {
                name: "",
                scopes: [],
                expiration_date: "",
            },
            errors: {},
            scopes: [],
            dialog: false,
            expanded: true,
        };
    },
    computed: {
        groupedScopes() {
            const grouped = {};
            this.scopes.forEach((scope) => {
                if (scope.id) {
                    const [group, service, role] = scope.id.split(":");
                    if (!grouped[group]) grouped[group] = {};
                    if (!grouped[group][service]) grouped[group][service] = [];
                    Object.assign(scope, { name: role });
                    grouped[group][service].push(scope);
                }
            });
            return grouped;
        },
    },

    methods: {
        async open() {
            this.getScopes();
            this.token = null;
            this.errors = {};
            this.dialog = true;
        },
        close() {
            this.token = null;
            this.errors = {};
            this.dialog = false;
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$q.notify({
                    type: "positive",
                    message: "Copied to clipboard",
                    timeout: 3000,
                });
            } catch (err) {}
        },
        async create() {
            this.token = null;
            try {
                const res = await this.$server.post(
                    this.$page.props.route,
                    this.form
                );
                if (res.status == 200) {
                    this.errors = {};
                    this.form.expiration_date = "";
                    this.form.name = "";
                    this.form.scopes = [];
                    this.token = res.data;
                    this.$emit("created");
                }
            } catch (e) {
                if (e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response.status == 404) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 5000,
                    });
                }
            }
        },
        getScopes() {
            this.$server
                .get("/oauth/scopes")
                .then((res) => {
                    this.scopes = res.data;
                })
                .catch(() => {});
        },
    },
};
</script>
<style scoped>
.checkbox-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 8px;
}
</style>
