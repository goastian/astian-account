<template>
    <q-dialog v-model="dialog" persistent>
        <q-card class="q-pa-md full-width">
            <!-- Header -->
            <q-card-section class="row items-center">
                <div class="text-h6 text-weight-bold">Assigned Scopes</div>
                <q-space />
                <q-btn flat icon="close" @click="dialog = false" />
            </q-card-section>

            <q-separator />

            <!-- Body -->
            <q-card-section class="q-gutter-y-md">
                <div>
                    <q-inner-loading :showing="loading" label="Please wait..." label-class="text-teal "
                        label-style="font-size: 2em" />
                </div>

                <div v-show="!loading" v-for="[groupName, services] in groupedRoles" :key="groupName" class="q-pa-md">
                    <div class="text-h5 q-mb-md text-ucfirst">
                        {{ groupName }}
                    </div>

                    <div v-for="[serviceName, roles] in Object.entries(services)" :key="serviceName"
                        class="q-mb-sm q-mb-md">
                        <div class="text-subtitle1 q-mb-sm text-ucfirst grey-3 q-rounded-borders shadow-1 q-pa-sm">
                            {{ serviceName }}

                            <div class="row q-col-gutter-sm q-ma-sm q-pa-md">
                                <q-card v-for="(item, index) in roles" :key="index" class="col-12 q-pa-sm q-ma-sm" flat
                                    bordered>
                                    <div class="flex justify-between">
                                        <div>
                                            <q-chip color="blue-4" text-color="white" square dense icon="mdi-key">
                                                {{ item.scope.role.name }}
                                            </q-chip>
                                            <div class="text-caption">
                                                {{
                                                    item.scope.role.description
                                                }}
                                            </div>
                                        </div>
                                        <div class="q-mt-sm">
                                            <q-btn outline round icon="mdi-delete-outline" color="negative"
                                                @click="confirmAction(item)" />
                                        </div>
                                    </div>
                                </q-card>
                            </div>
                        </div>
                    </div>
                </div>
            </q-card-section>

            <q-separator />

            <!-- Footer -->
            <q-card-actions align="right">
                <q-btn @click="dialog = false" color="positive" icon="close">
                    Close
                </q-btn>
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-dialog v-model="confirm" persistent>
        <q-card>
            <q-card-section class="row items-center">
                <span class="q-ml-sm">
                    Are you sure you want to remove this role?
                </span>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn outline label="Cancel" color="primary" v-close-popup />
                <q-btn outline label="Accept" icon="mdi-delete-outline" color="positive" @click="revoke" />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <!-- Trigger Button -->
    <q-btn outline round icon="mdi-shield-remove-outline" color="positive" @click="openDialog">
        <q-tooltip transition-show="rotate" transition-hide="rotate">
            View assigned scopes
        </q-tooltip>
    </q-btn>
</template>

<script>
export default {
    props: {
        item: {
            required: true,
            type: Object,
        },
    },

    data() {
        return {
            user_roles: [],
            dialog: false,
            confirm: false,
            selected_scope: {},
            loading: true,
        };
    },

    computed: {
        groupedRoles() {
            const grouped = {};

            for (const item of this.user_roles) {
                const group =
                    item.scope?.service?.group?.name || "Unknown Group";
                const service = item.scope?.service?.name || "Unknown Service";

                if (!grouped[group]) {
                    grouped[group] = {};
                }

                if (!grouped[group][service]) {
                    grouped[group][service] = [];
                }

                grouped[group][service].push(item);
            }

            return Object.entries(grouped);
        },
    },

    methods: {
        openDialog() {
            this.dialog = true;
            this.userRoles();
        },

        confirmAction(item) {
            this.selected_scope = item;
            this.confirm = true;
        },

        async userRoles() {
            try {
                const res = await this.$server.get(this.item.links.scopes, {
                    params: { per_page: 150 },
                });

                if (res.status === 200) {
                    this.user_roles = res.data.data;
                    this.loading = false;
                }
            } catch (error) { }
        },

        async revoke() {
            try {
                const res = await this.$server.put(
                    this.selected_scope.links.revoke
                );

                if (res.status === 200) {
                    this.userRoles();
                    this.confirm = false;
                    this.$q.notify({
                        type: "positive",
                        message: "Scope has been revoked",
                        timeout: 3000,
                    });
                }
            } catch (error) {
                if (error.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: error.response.data.message,
                        timeout: 3000,
                    });
                }
            }
        },
    },
};
</script>
