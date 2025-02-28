<template>
    <v-dialog max-width="500">
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
                <v-card-title> Add new user </v-card-title>
                <v-card-text>
                    <div class="grid grid-cols-1">
                        <!-- Name -->
                        <div class="w-full mb-2">
                            <input
                                placeholder="Name"
                                type="text"
                                v-model="form.name"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-300"
                            />
                            <v-error :error="errors.name"></v-error>
                        </div>

                        <!-- Last Name -->
                        <div class="w-full mb-2">
                            <input
                                placeholder="Last name"
                                type="text"
                                v-model="form.last_name"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-300"
                            />
                            <v-error :error="errors.last_name"></v-error>
                        </div>

                        <!-- Email -->
                        <div class="w-full mb-2">
                            <input
                                placeholder="Email"
                                type="email"
                                v-model="form.email"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-300"
                            />
                            <v-error :error="errors.email"></v-error>
                        </div>

                        <!-- Country -->
                        <div class="w-full mb-2">
                            <label
                                class="block text-gray-700 text-sm font-bold mb-2"
                            >
                                Country
                            </label>
                            <select
                                v-model="form.country"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-300"
                            >
                                <option
                                    v-for="country in countries"
                                    :key="country.name_en"
                                    :value="country.name_en"
                                >
                                    {{ country.emoji }} {{ country.name_en }}
                                </option>
                            </select>
                            <v-error :error="errors.country"></v-error>
                        </div>

                        <!-- Dial Code -->
                        <div class="w-full mb-2">
                            <label
                                class="block text-gray-700 text-sm font-bold mb-2"
                                >Dial Code</label
                            >
                            <select
                                v-model="form.dial_code"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-300"
                            >
                                <option
                                    v-for="country in countries"
                                    :key="country.dial_code"
                                    :value="country.dial_code"
                                >
                                    {{ country.emoji }}
                                    {{ country.name_en }} ({{
                                        country.dial_code
                                    }})
                                </option>
                            </select>
                            <v-error :error="errors.dial_code"></v-error>
                        </div>

                        <!-- Phone -->
                        <div class="w-full mb-2">
                            <input
                                placeholder="Phone Number"
                                type="text"
                                v-model="form.phone"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-300"
                            />
                            <v-error :error="errors.phone"></v-error>
                        </div>

                        <!-- Birthday -->
                        <div class="w-full mb-2">
                            <label
                                class="block text-gray-700 text-sm font-bold mb-2"
                                >Birthday</label
                            >
                            <VueDatePicker
                                v-model="birthday"
                                :enable-time-picker="false"
                                :max-date="new Date()"
                                format="yyyy-MM-dd"
                            />
                            <v-error :error="errors.birthday"></v-error>
                        </div>

                        <div class="w-full mb-2">
                            <label
                                class="block text-gray-700 text-sm font-bold mb-2"
                            >
                                Groups
                            </label>

                            <v-select
                                v-model="form.groups"
                                :items="groups"
                                item-title="name"
                                item-value="id"
                                label="Groups"
                                persistent-hint
                                multiple
                                single-line
                            ></v-select>

                            <v-error :error="errors.groups"></v-error>
                        </div>

                        <div class="w-full mb-2">
                            <v-checkbox
                                density="compact"
                                v-model="verify_email"
                                label="Mark user email as verified"
                                variant="solo"
                            ></v-checkbox>
                        </div>
                    </div>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        @click="create"
                        color="blue-darken-1"
                        prepend-icon="mdi-content-save-alert"
                        class="mx-4"
                        variant="flat"
                    >
                        Save
                    </v-btn>
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
                name: null,
                last_name: null,
                email: null,
                country: null,
                city: null,
                address: null,
                dial_code: null,
                phone: null,
                birthday: null,
                groups: [],
                verify_email: null,
            },
            errors: {},
            countries: [],
            groups: [],
            verify_email: false,
        };
    },

    watch: {
        verify_email(value) {
            this.item.verify_email = value ? 1 : 0;
        },
    },

    methods: {
        async calendar() {
            await nextTick();
            flatpickr(".datetime", {
                enableTime: true,
                dateFormat: "Y-m-d",
                maxDate: "today",
            });
        },

        /**
         * Show the modal in the windowss
         */
        async open() {
            await this.getCountries();
            await this.getGroups();
            await this.calendar();
        },

        /**
         *  reset keys when the windows is closed
         */
        close(isActive) {
            isActive.value = false;
            this.form = [];
            this.countries = [];
        },

        /**
         * Get the groups
         */
        async getGroups() {
            try {
                const res = await this.$server.get("/api/admin/groups", {
                    params: {
                        per_page: 150,
                    },
                });

                if (res.status == 200) {
                    this.groups = res.data.data;
                }
            } catch (error) {}
        },

        /**
         * Create a new user in the system
         *
         */
        async create() {
            try {
                const res = await this.$server.post(
                    "/api/admin/users",
                    this.form,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );

                if (res.status == 201) {
                    this.form = { scope: [] };
                    this.errors = {};
                    this.notification.success(
                        "A new user has been created successfully"
                    );
                    this.$emit("created", true);
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.data.errors &&
                    e.response.status == 422
                ) {
                    this.errors = e.response.data.errors;
                }

                if (
                    e.response &&
                    e.response.status != 422 &&
                    e.response.data &&
                    e.response.data.message
                ) {
                    this.notification.error(e.response.data.message);
                }
            }
        },

        /**
         * Get the all countries
         */
        async getCountries() {
            try {
                const res = await this.$server.get("/api/resources/countries", {
                    params: {
                        order_by: "name_en",
                        order_type: "asc",
                    },
                });

                if (res.status == 200) {
                    this.countries = res.data;
                }
            } catch (e) {}
        },
    },
};
</script>
