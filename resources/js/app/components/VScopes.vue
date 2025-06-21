<template>
    <div class="q-gutter-y-xl">
        <div>
            <q-inner-loading
                :showing="loading"
                label="Please wait..."
                label-class="text-teal "
                label-style="font-size: 2em"
            />
        </div>

        <div v-if="!loading">
            <div v-for="group in groupedScopes" :key="group.name">
                <q-card
                    flat
                    bordered
                    class="q-pa-lg q-gutter-y-md q-ml-sm q-mt-lg"
                >
                    <div class="text-h6 text-weight-bold text-ucfirst">
                        {{ group.name }}
                    </div>

                    <div class="text-caption text-positive q-mb-md q-mt-xs">
                        {{ group.description }}
                    </div>

                    <div class="q-gutter-y-md q-ml-sm q-mt-lg">
                        <q-card
                            v-for="(roles, service) in group.services"
                            :key="service"
                            class="q-pa-md"
                            bordered
                        >
                            <div class="row items-center q-mb-sm">
                                <span
                                    class="text-subtitle1 text-ucfirst text-weight-medium"
                                >
                                    {{ service }}
                                </span>
                                <q-space />
                                <span class="text-caption">
                                    {{ roles[0]?.service_description || "" }}
                                </span>
                            </div>

                            <div class="q-gutter-sm">
                                <q-checkbox
                                    v-for="role in roles"
                                    :key="role.id"
                                    v-model="selected_scopes"
                                    :val="role.id"
                                    :label="role.role_name"
                                    class="q-pa-sm"
                                >
                                    <q-tooltip>
                                        {{ role.role.description }}
                                    </q-tooltip>
                                </q-checkbox>
                            </div>
                        </q-card>
                    </div>
                </q-card>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    emits: ["checked"],
    props: ["default_roles"],

    data() {
        return {
            scopes: [],
            selected_scopes: [],
            user_scopes: [],
            loading: true,
        };
    },

    watch: {
        default_roles(values) {
            const scopes = values.map((userScope) => userScope.scope.id);
            this.selected_scopes.push(...scopes);
        },

        selected_scopes(newScopes) {
            this.$emit("checked", newScopes);
        },
    },

    mounted() {
        this.getScopes();
    },

    computed: {
        groupedScopes() {
            const grouped = {};

            this.scopes.forEach((scope) => {
                const groupName = scope.service.group.name;
                const groupDescription = scope.service.group.description;
                const serviceName = scope.service.name;

                scope.role_name = scope.role.name;
                scope.role_slug = scope.role.slug;
                scope.role_description = scope.role.description;
                scope.service_description = scope.service.description;

                if (!grouped[groupName]) {
                    grouped[groupName] = {
                        name: groupName,
                        description: groupDescription,
                        services: {},
                    };
                }

                if (!grouped[groupName].services[serviceName]) {
                    grouped[groupName].services[serviceName] = [];
                }

                grouped[groupName].services[serviceName].push(scope);
            });

            return Object.values(grouped);
        },
    },

    methods: {
        async getScopes() {
            try {
                const res = await this.$server.get(this.$page.props.scopes, {
                    params: { per_page: 150 },
                });

                if (res.status === 200) {
                    this.scopes = res.data.data;
                    this.syncSelectedScopes();
                    this.loading = false;
                }
            } catch (e) {
                console.log(e);
            }
        },

        syncSelectedScopes() {
            const defaultGsrIds = this.default_roles.map((role) => role.scope);
            this.user_scopes = this.scopes
                .filter((scope) => defaultGsrIds.includes(scope.gsr_id))
                .map((scope) => scope.id);
        },
    },
};
</script>
