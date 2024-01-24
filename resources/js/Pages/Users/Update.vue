<template>
    <!-- Card para mostrar y actualizar datos del usuario-->
    <v-modal
        :target="'__C__' + user.id"
        @is-clicked="loadData(user)"
        @is-accepted="update(user)"
    >
        <template v-slot:button> Detalle </template>
        <template v-slot:head> Datos del usuario {{ user.nombre }} </template>
        <template v-slot:body>
            <div class="row user-update">
                <div class="col mb-2">
                    <label for="nombre">nombre</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.nombre"
                        type="text"
                        name="nombre"
                        class="form-control"
                    />
                    <v-error :error="errors.nombre"></v-error>
                </div>
                <div class="col mb-2">
                    <label for="apellido">apellido</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.apellido"
                        type="text"
                        name="apellido"
                        class="form-control"
                    />
                    <v-error :error="errors.apellido"></v-error>
                </div>
                <div class="col mb-2">
                    <label for="">Correo</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.correo"
                        type="email"
                        name="correo"
                        class="form-control"
                    />
                    <v-error :error="errors.correo"></v-error>
                </div>

                <div class="col mb-2">
                    <label for="">Telefono</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.telefono"
                        type="text"
                        name="telefono"
                        class="form-control"
                    />
                    <v-error :error="errors.telefono"></v-error>
                </div>

                <div class="col mb-2">
                    <label for="pais">pais</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.pais"
                        type="text"
                        name="pais"
                        class="form-control"
                    />
                    <v-error :error="errors.pais"></v-error>
                </div>
                <div class="col mb-2">
                    <label for="ciudad">ciudad</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.ciudad"
                        type="text"
                        name="ciudad"
                        class="form-control"
                    />
                    <v-error :error="errors.ciudad"></v-error>
                </div>
                <div class="col mb-2">
                    <label for="direccion">direccion</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.direccion"
                        type="text"
                        name="direccion"
                        class="form-control"
                    />
                    <v-error :error="errors.direccion"></v-error>
                </div>
                <div class="col mb-2">
                    <label for="nacimiento">nacimiento</label>
                    <input
                        @keypress.enter="update(user)"
                        v-model="user.nacimiento"
                        type="date"
                        name="nacimiento"
                        class="form-control"
                    />
                    <v-error :error="errors.nacimiento"></v-error>
                </div>
                <div class="col mb-2">
                    <label for="registrado">registrado</label>
                    <p>{{ user.registrado }}</p>
                </div>
                <div class="col mb-2">
                    <label for="actualizado">actualizado</label>
                    <p>{{ user.actualizado }}</p>
                </div>
                <div class="col mb-2">
                    <label for="actualizado">Deshabilitado</label>
                    <p class="fw-bold text-primary">
                        {{
                            user.inactivo
                                ? `Usuario inactivo desde ${user.inactivo}`
                                : "Usuario Activo"
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
                <span class="text-light">{{ status }}</span>
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
<style lang="css" scoped>
.hide {
    display: none;
}

.show {
    display: block;
}
</style>
