<template>
    <!-- Card para mostrar y actualizar datos del usuario-->
    <v-modal
        :target="'__C__' + user.id"
        @is-clicked="loadData(user)"
        @is-accepted="update(user)"
    >
        <template v-slot:button> Details </template>
        <template v-slot:head>
            <p class="text-color text-uppercase fw-bold">
                INFORMATION ABOUT
                {{ user.name }} {{ user.last_name }}
            </p>
        </template>

        <template v-slot:body>
            <div class="user-update">
                <div class="item">
                    <div class="group">
                        <div>
                            <label class="text-color label">First Name</label>
                        </div>
                        <input
                            @keypress.enter="update(user)"
                            v-model="user.name"
                            type="text"
                        />
                    </div>
                    <v-error :error="errors.name"></v-error>
                </div>
                <div class="item">
                    <div class="group">
                        <div>
                            <label class="text-color label">Last Name</label>
                        </div>
                        <input
                            @keypress.enter="update(user)"
                            v-model="user.last_name"
                            type="text"
                        />
                    </div>
                    <v-error :error="errors.last_name"></v-error>
                </div>
                <div class="item">
                    <div class="group">
                        <div>
                            <label class="text-color label"
                                >Email Address</label
                            >
                        </div>
                        <input
                            @keypress.enter="update(user)"
                            v-model="user.email"
                            type="email"
                            class="input-theme"
                        />
                    </div>
                    <v-error :error="errors.email"></v-error>
                </div>

                <div class="item">
                    <div class="group">
                        <div>
                            <label class="label">Phone Number</label>
                        </div>
                        <div>
                            <v-select-search
                                class="label"
                                :items="countries"
                                param="name_en"
                                :text="user.dial_code"
                                @selected="setDialCode"
                                index="0"
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
                                        {{ slotProps.items.name_en }}
                                        {{ slotProps.items.dial_code }}
                                    </span>
                                </template>
                            </v-select-search>
                        </div>
                        <input
                            @keypress.enter="update(user)"
                            v-model="user.phone"
                            type="text"
                        />
                    </div>
                    <v-error :error="errors.phone"></v-error>
                </div>

                <div class="item">
                    <div class="group">
                        <div>
                            <label class="text-color label">Country</label>
                        </div>
                        <div>
                            <v-select-search
                                class="label"
                                :items="countries"
                                param="name_en"
                                :text="user.country"
                                @selected="setContry"
                            >
                                <template #title="slotProps">
                                    {{
                                        slotProps.item.name_en
                                            ? slotProps.item.emoji +
                                              " " +
                                              slotProps.item.name_en
                                            : slotProps.text
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
                            @keypress.enter="update(user)"
                            v-model="user.country"
                            type="text"
                            class="input-theme"
                        />
                    </div>
                    <v-error :error="errors.country"></v-error>
                </div>
                <div class="item">
                    <div class="group">
                        <div>
                            <label class="label">City Or State</label>
                        </div>
                        <input
                            @keypress.enter="update(user)"
                            v-model="user.city"
                            type="text"
                            class="input-theme"
                        />
                    </div>
                    <v-error :error="errors.city"></v-error>
                </div>
                <div class="item">
                    <div class="group">
                        <div>
                            <label class="text-color label">Home Address</label>
                        </div>
                        <input
                            @keypress.enter="update(user)"
                            v-model="user.address"
                            type="text"
                            class="input-theme"
                        />
                    </div>
                    <v-error :error="errors.address"></v-error>
                </div>
                <div class="item">
                    <div class="group">
                        <div>
                            <label class="text-color label"
                                >Date of birth</label
                            >
                        </div>
                        <input
                            @keypress.enter="update(user)"
                            v-model="user.birthday"
                            type="date"
                            class="input-theme"
                        />
                    </div>
                    <v-error :error="errors.birthday"></v-error>
                </div>
                <div class="item">
                    <div class="group">
                        <div>
                            <label class="label">Join us</label>
                        </div>
                        <span>{{ user.created }}</span>
                    </div>
                </div>
                <div class="item">
                    <div class="group">
                        <div>
                            <label class="text-color label">Last Update</label>
                        </div>
                        <span>{{ user.updated }}</span>
                    </div>
                </div>
                <div class="item">
                    <div class="group">
                        <div>
                            <label class="text-color label">Disabled</label>
                        </div>
                        <span class="text-color">
                            {{
                                user.disabled
                                    ? `User inactive since ${user.disabled}`
                                    : "Active User"
                            }}
                        </span>
                    </div>
                </div>
            </div>

            <div
                class="col-12 bg-light text-center fw-bold mx-2 py-3"
                :class="{ show: status, hide: !status }"
            >
                <span class="text-color">{{ status }}</span>
            </div>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["success"],

    props: ["user"],

    data() {
        return {
            status: false,
            errors: {},
            roles: {},
            client: false,
            countries: {},
        };
    },

    mounted() {
        this.getCountries();
    },

    methods: {
        setContry(event) {
            this.user.country = event.name_en;
        },

        setDialCode(event) {
            this.user.dial_code = event.dial_code;
        },
        /**
         * agrega o elimina permisos
         */
        addOrRemoveRoles(id) {
            this.status = null;
            const checked = document.getElementById(id).checked;

            if (checked) {
                this.$server
                    .post(this.user.links.roles, { role: id })
                    .then((res) => {
                        this.status = `New rol ${res.data.data.role} has been added`;
                    })
                    .catch((e) => {
                        if (e.response && e.response.data.data.status == 403) {
                            this.status = e.response.data.data.message;
                        }
                    });
            } else {
                this.$server
                    .delete(`${this.user.links.roles}/${id}`)
                    .then((res) => {
                        this.status = `The role ${res.data.data.role} was removed`;
                    })
                    .catch((e) => {
                        if (e.response && e.response.status == 403) {
                            if (e.response && e.response.data.data.message) {
                                this.status = e.response.data.data.message;
                            }

                            if (e.response && e.response.data.errors) {
                                this.errors = e.response.data.errors;
                            }
                        }
                    });
            }
        },

        loadData(user) {
            this.authenticated();
            this.getRoles();
            this.getUserRoles(user);
        },

        getRoles() {
            this.$server
                .get("/api/roles")
                .then((res) => {
                    this.roles = res.data.data;
                })
                .catch((e) => {
                    if (e.response && e.response.status == 401) {
                        window.location.href = "/login";
                    }
                });
        },

        /**
         * Actualiza a un usuario
         * @param {*} item
         */
        update(item) {
            this.status = null;
            this.$server
                .put(item.links.update, this.user)
                .then((res) => {
                    this.status = "User updated";
                    this.$emit("success", res.data.data);
                    this.errors = {};
                })
                .catch((e) => {
                    if (
                        e.response &&
                        e.response.data.errors &&
                        e.response.status == 422
                    ) {
                        this.errors = e.response.data.errors;
                    }
                    if (e.response && e.response.status == 403) {
                        this.status =
                            "Without authorization to perform this action";
                    }
                    if (e.response && e.response.status == 401) {
                        window.location.href = "/login";
                    }
                });
        },

        authenticated() {
            this.$server
                .get("/api/gateway/user")
                .then((res) => {
                    this.client = res.data.cliente;
                })
                .catch((e) => {
                    if (e.response && e.response.status == 401) {
                        window.location.href = "/login";
                    }
                });
        },

        /**
         * Obtiene los roles de un usuario
         */
        getUserRoles(item) {
            this.status = null;
            this.$server
                .get(item.links.roles)
                .then((res) => {
                    this.role_selected(res.data.data);
                })
                .catch((e) => {});
        },

        /**
         * selecciona los roles del usuario
         * @param {*} objetos
         */
        role_selected(objetos) {
            const roles = document.querySelectorAll(".form-check-input");

            for (let i = 0; i < roles.length; i++) {
                roles[i].checked = false;
            }

            for (let i = 0; i < objetos.length; i++) {
                for (let j = 0; j < roles.length; j++) {
                    if (roles[j].value == objetos[i]["id"]) {
                        roles[j].checked = true;
                    }
                }
            }
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
.user-update {
    display: flex;
    flex-wrap: wrap;

    .item {
        flex: 1 1 calc(100% / 2);
        padding: 0.3em 0.3em;

        input {
            padding: 0.5em 0.3em !important;
        }
    }
}

.hide {
    display: none;
}

.show {
    display: block;
}
</style>
