<template>
    <v-dialog>
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                v-bind="activatorProps"
                color="blue-lighten-1"
                icon
                variant="tonal"
                @click="open"
            >
                <v-icon icon="mdi-plus"></v-icon>
            </v-btn>
        </template>

        <template v-slot:default="{ isActive }">
            <v-card>
                <v-card-title> Generate a new API KEY </v-card-title>
                <v-card-text>
                    <div class="w-full mb-4">
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                        >
                            <div>
                                <label
                                    class="text-gray-600 font-medium"
                                    for="name"
                                    >Name</label
                                >
                                <input
                                    id="name"
                                    type="text"
                                    placeholder="Name"
                                    v-model="form.name"
                                    class="mt-1 py-2 px-2 w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                />
                                <v-error :error="errors.name"></v-error>
                            </div>
                            <div>
                                <label class="text-gray-600 font-medium block">
                                    Expiration date
                                </label>
                                <input
                                    type="text"
                                    v-model="form.expiration_date"
                                    class="mt-1 datetime py-2 px-2 w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Select date and time"
                                />
                                <v-error
                                    :error="errors.expiration_date"
                                ></v-error>
                            </div>

                            <div class="flex align-end">
                                <v-btn
                                    @click="create"
                                    color="blue-darken-1"
                                    prepend-icon="mdi-content-save-alert"
                                    class="mx-4"
                                    variant="flat"
                                >
                                    Create
                                </v-btn>
                            </div>

                            <div class="">
                                <div
                                    v-if="token"
                                    @click="copyToClipboard(token.accessToken)"
                                    class="flex align-content-around align-center bg-green-400 cursor-pointer text-white rounded-2xl pa-2"
                                >
                                    <i
                                        class="mdi mdi-content-copy text-3xl"
                                    ></i>
                                    <span>
                                        Please copy this token and save it in a
                                        secure place
                                    </span>
                                </div>

                                <v-snackbar v-if="snackbar" v-model="snackbar">
                                    Copied to clipboard
                                </v-snackbar>
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="space-y-4">
                            <div
                                v-for="(services, group) in groupedScopes"
                                :key="group"
                                class="mb-5"
                            >
                                <h2
                                    class="text-lg font-semibold uppercase text-gray-700"
                                >
                                    {{ group }}
                                </h2>
                                <div
                                    v-for="(roles, service) in services"
                                    :key="service"
                                    class="ml-4 px-4 py-2 mb-2 bg-gray-100 rounded-lg"
                                >
                                    <div class="flex items-center mt-4 gap-2">
                                        <h3
                                            class="text-md uppercase font-medium text-gray-600"
                                        >
                                            {{ service }}
                                        </h3>
                                    </div>
                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-2"
                                    >
                                        <label
                                            v-for="role in roles"
                                            :key="role.id"
                                            class="flex items-center gap-2 p-2 rounded"
                                        >
                                            <input
                                                type="checkbox"
                                                v-model="form.scopes"
                                                :value="role.id"
                                                class="w-4 h-4 text-blue-500 border-gray-300 rounded"
                                            />
                                            <span
                                                class="text-gray-700 text-sm relative group"
                                            >
                                                <v-tooltip
                                                    :text="role.description"
                                                >
                                                    <template
                                                        v-slot:activator="{
                                                            props,
                                                        }"
                                                    >
                                                        <span v-bind="props">
                                                            {{ role.name }}
                                                        </span>
                                                    </template>
                                                </v-tooltip>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        @click="close(isActive)"
                        prepend-icon="mdi-close-circle"
                        variant="flat"
                        color="red-lighten-1"
                    >
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </template>
    </v-dialog>
</template>
<script>
import flatpickr from "flatpickr";
import { nextTick } from "vue";

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
            snackbar: false,
        };
    },

    computed: {
        groupedScopes() {
            const grouped = {};
            this.scopes.forEach((scope) => {
                if (scope.id) {
                    const [group, service, role] = scope.id.split("_");

                    if (!grouped[group]) {
                        grouped[group] = {};
                    }

                    if (!grouped[group][service]) {
                        grouped[group][service] = [];
                    }

                    Object.assign(scope, { name: role });
                    grouped[group][service].push(scope);
                }
            });
            return grouped;
        },
    },

    mounted() {
        this.listenEvent();
    },

    methods: {
        async open() {
            this.getScopes();
            this.token = null;
            this.errors = {};
            await this.calendar();
        },

        close(isActive) {
            this.token = null;
            this.errors = {};
            isActive.value = false;
        },

        async calendar() {
            await nextTick();
            flatpickr(".datetime", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
            });
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.snackbar = true;
            } catch (err) {}
        },

        async create() {
            try {
                const res = await this.$server.post(
                    "/oauth/api-keys",
                    this.form
                );

                if (res.status == 200) {
                    this.errors = {};
                    this.form.expiration_date = null;
                    this.form.name = null;
                    this.form.scopes = [];
                    this.token = res.data;
                    this.$emit("created");
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        },

        getScopes() {
            this.$server
                .get("/api/oauth/scopes")
                .then((res) => {
                    this.scopes = res.data;
                })
                .catch((e) => {});
        },

        listenEvent() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen(".StoreRoleEvent", (e) => {
                    this.getScopes();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen(".UpdateRoleEvent", (e) => {
                    this.getScopes();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen(".DestroyRoleEvent", (e) => {
                    this.getScopes();
                });
        },
    },
};
</script>
