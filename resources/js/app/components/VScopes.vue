<template>
    <div class="space-y-4">
        <div
            v-for="(services, group) in groupedScopes"
            :key="group"
            class="mb-5"
        >
            <h6 class="text-lg font-semibold uppercase text-gray-700">
                {{ group }}
            </h6>
            <div
                v-for="(roles, service) in services"
                :key="service"
                class="ml-4 px-4 py-1 mb-6 bg-gray-100 rounded-lg"
            >
                <div class="flex items-center mt-4 gap-1">
                    <h6 class="text-md uppercase font-medium text-gray-600">
                        {{ service }}
                    </h6>
                    -
                    <span class="text-sm text-gray-500">{{
                        roles[0].service_description
                    }}</span>
                </div>
                <div class="grid grid-cols-3 gap-2 mt-2">
                    <label
                        v-for="role in roles"
                        :key="role.id"
                        :class="{
                            'bg-blue-200 p-2 font-bold text-blue-600':
                                user_scopes.includes(role.id),
                            'bg-transparent': !user_scopes.includes(role.id),
                        }"
                        class="flex items-center gap-2 p-2 rounded"
                    >
                        <input
                            type="checkbox"
                            v-model="selected_scopes"
                            :value="role.id"
                            class="w-4 h-4 text-blue-500 border-gray-300 rounded"
                        />
                        <span class="text-gray-700 text-sm relative group">
                            <v-tooltip :text="role.role_description">
                                <template v-slot:activator="{ props }">
                                    <span v-bind="props">
                                        {{ role.role_slug }}
                                    </span>
                                </template>
                            </v-tooltip>
                        </span>
                    </label>
                </div>
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
        };
    },

    watch: {
        default_roles: {
            immediate: true,
            handler(values) {
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

                if (res.status == 200) {
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
