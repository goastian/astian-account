<template>
    <v-dialog max-width="500">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                v-bind="activatorProps"
                color="blue-lighten-1"
                icon
                variant="tonal"
                @click="loadData(item)"
            >
                <v-icon icon="mdi-pencil"></v-icon>
            </v-btn>
        </template>

        <template v-slot:default="{ isActive }">
            <v-card>
                <v-card-title> Update user </v-card-title>
                <v-card-text>
                    <div class="grid grid-cols-1">
                        <!-- Name -->
                        <div class="w-full mb-2">
                            <input
                                placeholder="Name"
                                type="text"
                                v-model="item.name"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-300"
                            />
                            <v-error :error="errors.name"></v-error>
                        </div>

                        <!-- Last Name -->
                        <div class="w-full mb-2">
                            <input
                                placeholder="Last name"
                                type="text"
                                v-model="item.last_name"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-300"
                            />
                            <v-error :error="errors.last_name"></v-error>
                        </div>

                        <!-- Email -->
                        <div class="w-full mb-2">
                            <input
                                placeholder="Email"
                                type="email"
                                v-model="item.email"
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
                                v-model="item.country"
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
                                v-model="item.dial_code"
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
                                v-model="item.phone"
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
                        @click="update"
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
export default {
    emits: ["updated"],

    props: {
        item: {
            required: true,
            type: Object,
        },
    },

    data() {
        return {
            errors: {},
            countries: [],
            verify_email: false,
            birthday: null,
        };
    },

    watch: {
        verify_email(value) {
            this.item.verify_email = value ? 1 : 0;
        },
    },

    methods: {
        /**
         *  reset keys when the windows is closed
         */
        close(isActive) {
            isActive.value = false;
            this.countries = [];
        },

        /**
         * Load necessary data to register new users
         */
        async loadData(item) {
            this.verify_email = item.verify_email;
            await this.getCountries();
        },

        /**
         * update the user in the system
         *
         */
        async update() {
            try {
                const res = await this.$server.put(
                    this.item.links.update,
                    this.item,
                    {
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                    }
                );

                if (res.status == 200) {
                    this.form = { scope: [] };
                    this.errors = {};
                    this.$emit("updated", true);
                    this.$notification.success(
                        "User has been updated successfully"
                    );
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
                    this.$notification.error(e.response.data.message);
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
