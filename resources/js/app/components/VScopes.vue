<template>
    <div class="q-gutter-y-md">
        <div
            v-for="(services, group) in groupedScopes"
            :key="group"
            class="q-mb-md"
        >
            <q-banner
                class="text-h6 text-grey-8 bg-grey-3 q-pa-md rounded-borders"
            >
                {{ group }}
            </q-banner>

            <q-card
                v-for="(roles, service) in services"
                :key="service"
                class="q-mt-md q-pa-md bg-grey-2"
            >
                <div class="row items-center q-mb-md">
                    <span class="text-subtitle1 text-weight-medium text-grey-7">
                        {{ service }}
                    </span>
                    <q-space />
                    <span class="text-caption text-grey-6">
                        {{ roles[0].service_description }}
                    </span>
                </div>

                <div class="q-gutter-sm">
                    <q-checkbox
                        v-for="role in roles"
                        :key="role.id"
                        v-model="selected_scopes"
                        :val="role.id"
                        :label="role.role_slug"
                        class="q-pa-sm"
                        :color="user_scopes.includes(role.id) ? 'blue' : 'grey'"
                    >
                        <q-tooltip>{{ role.role_description }}</q-tooltip>
                    </q-checkbox>
                </div>
            </q-card>
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
        };
    },

    watch: {
        default_roles: {
            immediate: true,
            handler() {
                if (this.scopes.length) {
                    this.syncSelectedScopes();
                }
            },
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
                if (scope.gsr_id) {
                    const [group, service] = scope.gsr_id.split("_");

                    if (!grouped[group]) {
                        grouped[group] = {};
                    }

                    if (!grouped[group][service]) {
                        grouped[group][service] = [];
                    }

                    grouped[group][service].push(scope);
                }
            });
            return grouped;
        },
    },

    methods: {
        async getScopes() {
            try {
                const res = await this.$server.get("/api/admin/scopes", {
                    params: { per_page: 150 },
                });

                if (res.status === 200) {
                    this.scopes = res.data.data;
                    this.syncSelectedScopes();
                }
            } catch (e) {}
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
