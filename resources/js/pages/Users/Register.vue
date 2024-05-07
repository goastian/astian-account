<template>
    <v-modal target="create" @is-accepted="createUser">
        <template v-slot:button> New user </template>
        <template v-slot:head>
            <span class="text-uppercase text-center fw-bold"
                >panel to add new users</span
            >
        </template>
        <template v-slot:body>
            <div class="user-register">
                <div class="input">
                    <input
                        placeholder="First Name"
                        class="input-theme"
                        type="text"
                        v-model="form.name"
                    />
                    <v-error :error="errors.name"></v-error>
                </div>
                <div class="input">
                    <input
                        type="text"
                        placeholder="Last Name"
                        v-model="form.last_name"
                        class="input-theme"
                    />
                    <v-error :error="errors.last_name"></v-error>
                </div>
                <div class="input">
                    <input
                        type="email"
                        v-model="form.email"
                        placeholder="Email Address"
                        class="input-theme"
                    />
                    <v-error :error="errors.email"></v-error>
                </div>
                <div class="input">
                    <div class="group">
                        <div>
                            <v-select-search
                                class="label"
                                :items="countries"
                                param="name_en"
                                text="Country"
                                @selected="setCountry"
                            >
                                <template #title="slotProps">
                                    {{
                                        slotProps.item.name_en
                                            ? slotProps.item.emoji
                                            : null
                                    }}
                                </template>

                                <template #options="slotProps">
                                    <span class="">
                                        {{ slotProps.items.emoji }}
                                        {{ slotProps.items.name_en }}
                                    </span>
                                </template>
                            </v-select-search>
                        </div>
                        <input
                            type="text"
                            placeholder="Country"
                            v-model="form.country"
                        />
                    </div>
                    <v-error :error="errors.country"></v-error>
                </div>
                <div class="input">
                    <input
                        type="text"
                        v-model="form.city"
                        placeholder="City"
                        class="input-theme"
                    />
                    <v-error :error="errors.city"></v-error>
                </div>
                <div class="input">
                    <input
                        type="text"
                        v-model="form.address"
                        placeholder="Home Address"
                        class="input-theme"
                    />
                    <v-error :error="errors.address"></v-error>
                </div>
                <div class="input">
                    <div class="group">
                        <div>
                            <v-select-search
                                class="label"
                                :items="countries"
                                param="name_en"
                                text="Dial code"
                                @selected="setCode"
                            >
                                <template #title="slotProps">
                                    {{
                                        slotProps.item.name_en
                                            ? slotProps.item.emoji +
                                              " " +
                                              slotProps.item.name_en +
                                              " " +
                                              slotProps.item.dial_code
                                            : slotProps.text
                                    }}
                                </template>

                                <template #options="slotProps">
                                    <span class="">
                                        {{ slotProps.items.emoji }}
                                        {{ slotProps.items.dial_code }}
                                        {{ slotProps.items.name_en }}
                                    </span>
                                </template>
                            </v-select-search>
                        </div>
                        <input
                            type="text"
                            v-model="form.phone"
                            placeholder="Phone Number"
                        />
                    </div>
                    <v-error :error="errors.phone"></v-error>
                    <v-error :error="errors.dial_code"></v-error>
                </div>
                <div class="input">
                    <input
                        type="date"
                        v-model="form.birthday"
                        placeholder="Birthday"
                        class="input-theme"
                    />
                    <v-error :error="errors.birthday"></v-error>
                </div>
            </div>
            <div class="m-2 p-2">
                <span class="">User Scopes</span>
            </div>
            <div class="user-scopes">
                <div
                    class="form-check"
                    v-for="(item, index) in roles"
                    :key="index"
                    v-show="!item.public"
                >
                    <input
                        class="form-check-input"
                        type="checkbox"
                        :value="item.id"
                        :id="index"
                        v-model="form.scope"
                    />
                    <label class="form-check-label text-sm" :for="index">
                        <strong class="text-color">{{ item.scope }}: </strong>
                        <span>{{ item.description }}</span>
                    </label>
                </div>
            </div>

            <div>
                <v-error :error="errors.scope"></v-error>
            </div>
            <v-message :message="message" @close="close"></v-message>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["success", "errors"],

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
                scope: [],
            },
            errors: {},
            roles: {},
            message: null,
            countries: {},
        };
    },

    mounted() {
        this.getRoles();
        this.getCountries();
    },

    methods: {
        setCountry(event) {
            this.form.country = event.name_en;
        },

        setCode(event) {
            this.form.dial_code = event.dial_code;
        },

        close() {
            this.message = null;
        },
        getRoles() {
            this.$server
                .get("/api/admin/roles")
                .then((res) => {
                    this.roles = res.data.data;
                })
                .catch((e) => {
                    if (e.response && e.response.status == 401) {
                        window.location.href = "/login";
                    }
                });
        },

        createUser() {
            this.$server
                .post("/api/admin/users", this.form)
                .then((res) => {
                    this.message = "A new user has been registered";
                    this.form = { scope: [] };
                    this.errors = {};
                    this.$emit("success", res.data.data);
                })
                .catch((e) => {
                    if (e.response && e.response.data.errors) {
                        this.errors = e.response.data.errors;
                    }
                    if (e.response && e.response.status == 401) {
                        window.location.href = "/login";
                    }
                });
        },

        getCountries() {
            this.$server
                .get("/api/locations/countries")
                .then((res) => {
                    this.countries = res.data;
                })
                .catch((err) => {});
        },
    },
};
</script>

<style lang="scss" scoped>
.user-register {
    @media (min-width: 800px) {
        flex-wrap: wrap;
        display: flex;
    }

    .input {
        flex: 1 1 calc(100% / 2);
        padding: 0.2em 0.5em;
    }
}

.user-scopes {
    @media (min-width: 800px) {
        flex-wrap: wrap;
        display: flex;
    }

    .form-check {
        flex: 1 1 calc(100% / 2);
        padding: 0em 2em;
    }
}
</style>
