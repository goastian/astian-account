<template>
    <!-- Card para mostrar y actualizar datos del usuario-->
    <v-modal
        :target="'__C__' + user.id"
        @is-clicked="loadData(user)"
        @is-accepted="update(user)"
    >
        <template v-slot:button> Details </template>
        <template v-slot:head>
            <p class="text-color">
                About

                {{ user.name }}
            </p>
        </template>

        <template v-slot:body>
            <div class="row">
                <div class="col">
                    <label>First Name</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.name"
                        type="text"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.name"></v-error>
                </div>
                <div class="col">
                    <label>Last Name</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.last_name"
                        type="text"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.last_name"></v-error>
                </div>
                <div class="col">
                    <label>Email Address</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.email"
                        type="email"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.email"></v-error>
                </div>

                <div class="col">
                    <label>Phone Number</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.phone"
                        type="text"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.phone"></v-error>
                </div>

                <div class="col">
                    <label>Country</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.country"
                        type="text"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.country"></v-error>
                </div>
                <div class="col">
                    <label>City Or State</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.city"
                        type="text"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.city"></v-error>
                </div>
                <div class="col">
                    <label>Home Address</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.address"
                        type="text"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.address"></v-error>
                </div>
                <div class="col">
                    <label>Date of birth</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.birthday"
                        type="date"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.birthday"></v-error>
                </div>
                <div class="col">
                    <label>Join us</label>
                    <p>{{ user.created }}</p>
                </div>
                <div class="col">
                    <label>Last Update</label>
                    <p>{{ user.updated }}</p>
                </div>
                <div class="col">
                    <label>Disabled</label>
                    <p class="fw-bold text-color">
                        {{
                            user.disabled
                                ? `User inactive since ${user.disabled}`
                                : "Active User"
                        }}
                    </p>
                </div>
            </div>

            <div
                :class="[
                    'col-12 bg-success text-center mx-2 py-3',
                    [status ? 'show' : 'hide'],
                ]"
            >
                <p class="text-color">{{ status }}</p>
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
            message: null,
            status: false,
            errors: {},
            roles: {},
            client: false,
        };
    },

    methods: {
        /**
         * agrega o elimina permisos
         */
        addOrRemoveRoles(id) {
            this.message = null;
            this.status = null;
            const checked = document.getElementById(id).checked;

            if (checked) {
                this.$server
                    .post(this.user.links.roles, { role: id })
                    .then((res) => {
                        this.message = `Se asigno un nuevo role ${res.data.data.role} ha sido asignado`;
                    })
                    .catch((e) => {
                        if (e.response && e.response.data.data.status == 403) {
                            this.message = e.response.data.data.message;
                        }
                    });
            } else {
                this.$server
                    .delete(`${this.user.links.roles}/${id}`)
                    .then((res) => {
                        this.message = `Se elimino el role ${res.data.data.role}`;
                    })
                    .catch((e) => {
                        if (e.response && e.response.status == 403) {
                            if (e.response && e.response.data.data.message) {
                                this.message = e.response.data.data.message;
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
                .catch((e) => {});
        },

        /**
         * Actualiza a un usuario
         * @param {*} item
         */
        update(item) {
            this.status = null;
            this.message = null;
            this.$server
                .put(item.links.update, this.user)
                .then((res) => {
                    this.status = "Usuario Actualizado";
                    this.$emit("success", res.data.data);
                    this.errors = {};
                })
                .catch((e) => {
                    if (e.response && e.response.data.errors) {
                        this.errors = e.response.data.errors;
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
                    console.log(e);
                });
        },

        /**
         * Obtiene los roles de un usuario
         */
        getUserRoles(item) {
            this.message = null;
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
    },
};
</script>
<style lang="scss" scoped>
.col {
    flex: 0 0 auto;
    text-align: left;

    @media (min-width: 240px) {
        width: 98%;
        margin-bottom: 3%;
    }

    @media (min-width: 800px) {
        margin-bottom: 1%;
        width: 48%;
        
    }

    @media (min-width: 940px) {
        width: 30%;
        
    }
}

label {
    display: block !important;
    text-align: justify;
}
.hide {
    display: none;
}

.show {
    display: block;
}
</style>
