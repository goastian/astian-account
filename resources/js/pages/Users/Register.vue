<template>
    <v-modal target="create" @is-accepted="createUser" @is-clicked="loadData">
        <template v-slot:button> add new user </template>
        <template v-slot:head>
            <span class="text-uppercase text-center fw-bold"
                >panel to add new users</span
            >
        </template>
        <template v-slot:body>
            <div class="user-register">
                <div class="inputs">
                    <div class="item">
                        <label for=""></label>
                        <input
                            placeholder="First Name"
                            class="input"
                            type="text"
                            v-model="form.name"
                        />
                        <v-error :error="errors.name"></v-error>
                    </div>
                    <div class="item">
                        <input
                            type="text"
                            placeholder="Last Name"
                            v-model="form.last_name"
                            class="input"
                        />
                        <v-error :error="errors.last_name"></v-error>
                    </div>
                    <div class="item">
                        <input
                            type="email"
                            v-model="form.email"
                            placeholder="Email Address"
                            class="input"
                        />
                        <v-error :error="errors.email"></v-error>
                    </div>
                    <div class="item">
                        <div class="group">
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

                            <input
                                type="text"
                                placeholder="Country"
                                v-model="form.country"
                            />
                        </div>
                        <v-error :error="errors.country"></v-error>
                    </div>

                    <div class="item">
                        <input
                            type="text"
                            v-model="form.city"
                            placeholder="City"
                            class="input"
                        />
                        <v-error :error="errors.city"></v-error>
                    </div>
                    <div class="item">
                        <input
                            type="text"
                            v-model="form.address"
                            placeholder="Home Address"
                            class="input"
                        />
                        <v-error :error="errors.address"></v-error>
                    </div>
                    <div class="item">
                        <div class="group">
                            <v-select-search
                                class="label"
                                :items="countries"
                                text="Dial code"
                                @selected="setCode"
                                param="name_en"
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

                            <input
                                class="form-input"
                                type="text"
                                v-model="form.phone"
                                placeholder="Phone Number"
                            />
                        </div>
                        <v-error :error="errors.phone"></v-error>
                        <v-error :error="errors.dial_code"></v-error>
                    </div>
                    <div class="item">
                        <input
                            type="date"
                            v-model="form.birthday"
                            placeholder="Birthday"
                            class="input"
                        />
                        <v-error :error="errors.birthday"></v-error>
                    </div>
                </div>

                <div class="scopes">
                    <div class="head">
                        <p>Scopes</p>
                    </div>
                    <div class="items">
                        <div
                            class="form-check"
                            v-for="(item, index) in roles"
                            :key="index"
                            v-show="!item.public"
                        >
                            <div>
                                <input
                                    type="checkbox"
                                    :value="item.id"
                                    :id="index"
                                    v-model="form.scope"
                                />
                                <label :for="index">
                                    <strong>{{ item.scope }}: </strong>
                                    <span>{{ item.description }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <v-error :error="errors.scope"></v-error>
            </div>
            <v-message :id="message_show" @close="close">
                <template v-slot:body> {{ message }} </template>
            </v-message>
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
            message_show: null,
            countries: {},
        };
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

        loadData() {
            this.getRoles();
            this.getCountries();
        },

        async getRoles() {
            try {
                const res = await this.$server.get("/api/admin/roles");

                if (res.status == 200) {
                    this.roles = res.data.data;
                }
            } catch (e) {}
        },

        async createUser() {
            try {
                const res = await this.$server.post(
                    "/api/admin/users",
                    this.form
                );

                if (res.status == 201) {
                    this.message = "A new user has been registered";
                    this.message_show = Math.floor(Math.random() * 10000);
                    this.form = { scope: [] };
                    this.errors = {};
                    this.$emit("success", true);
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.data.errors &&
                    e.response.status == 422
                ) {
                    this.errors = e.response.data.errors;
                }
            }
        },

        async getCountries() {
            try {
                const res = await this.$server.get("/api/locations/countries");

                if (res.status == 200) {
                    this.countries = res.data;
                }
            } catch (e) {}
        },
    },
};
</script>

<style lang="scss" scoped>
.user-register {
    .inputs {
        display: flex;
        flex-wrap: wrap;
        padding: 0 0.3em;
        .item {
            width: 100%;
            @media (min-width: 800px) {
                flex: 0 0 calc(100% / 2);
            }
        }
    }

    .scopes {
        .head {
            p {
                font-size: 1.2em;
                margin: 0.5em 0;
                border-bottom: 1px solid var(--border-color-light);
                border-top: 1px solid var(--border-color-light);
                font-weight: bold;
            }
        }

        .items {
            display: flex;
            flex-wrap: wrap;
            padding: 0em;

            .form-check {
                @media (min-width: 800px) {
                    flex: 0 0 calc(100% / 2);
                }
                div {
                    display: flex;
                    input {
                        flex: 0 0 auto;
                    }
                    label {
                        flex: 1 1 auto;
                        color: var(--first-color);
                        font-size: 0.9em;
                        text-transform: lowercase;
                        strong {
                            font-weight: light;
                        }
                        span {
                            font-weight: lighter;
                        }
                    }
                }
            }
        }
    }
}
</style>
