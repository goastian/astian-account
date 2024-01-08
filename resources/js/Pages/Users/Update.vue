<template>
    <!-- Card para mostrar y actualizar datos del usuario-->
    <v-modal
        :target="'__C__' + user.id"
        @is-clicked="loadData(user)"
        @is-accepted="update(user)"
        width="modal-xl"
    >
        <template v-slot:button> Detalle </template>
        <template v-slot:head> Datos del usuario {{ user.nombre }} </template>
        <template v-slot:body>
            <div class="row" style="font-size: 12px">
                <div class="row row-cols-1 col-12">
                    <div class="col mb-2">
                        <div class="row row-cols-3 col-12">
                            <div class="col mb-2">
                                <label
                                    class="text-capitalize fw-bold"
                                    for="nombre"
                                    >nombre</label
                                >
                                <input
                                    @keypress.enter="update(user)"
                                    v-model="user.nombre"
                                    type="text"
                                    name="nombre"
                                    class="form-control-sm form-control"
                                />
                                <v-error :error="errors.nombre"></v-error>
                            </div>
                            <div class="col mb-2">
                                <label
                                    class="text-capitalize fw-bold"
                                    for="apellido"
                                    >apellido</label
                                >
                                <input
                                    @keypress.enter="update(user)"
                                    v-model="user.apellido"
                                    type="text"
                                    name="apellido"
                                    class="form-control-sm form-control"
                                />
                                <v-error :error="errors.apellido"></v-error>
                            </div>
                            <div class="col mb-2">
                                <label class="text-capitalize fw-bold" for=""
                                    >Correo</label
                                >
                                <input
                                    @keypress.enter="update(user)"
                                    v-model="user.correo"
                                    type="email"
                                    name="correo"
                                    class="form-control-sm form-control"
                                />
                                <v-error :error="errors.correo"></v-error>
                            </div>

                            <div class="col mb-2">
                                <label class="text-capitalize fw-bold" for=""
                                    >Telefono</label
                                >
                                <input
                                    @keypress.enter="update(user)"
                                    v-model="user.telefono"
                                    type="text"
                                    name="telefono"
                                    class="form-control-sm form-control"
                                />
                                <v-error :error="errors.telefono"></v-error>
                            </div>

                            <div class="col mb-2">
                                <label
                                    class="text-capitalize fw-bold"
                                    for="pais"
                                    >pais</label
                                >
                                <input
                                    @keypress.enter="update(user)"
                                    v-model="user.pais"
                                    type="text"
                                    name="pais"
                                    class="form-control-sm form-control"
                                />
                                <v-error :error="errors.pais"></v-error>
                            </div>
                            <div class="col mb-2">
                                <label
                                    class="text-capitalize fw-bold"
                                    for="ciudad"
                                    >ciudad</label
                                >
                                <input
                                    @keypress.enter="update(user)"
                                    v-model="user.ciudad"
                                    type="text"
                                    name="ciudad"
                                    class="form-control-sm form-control"
                                />
                                <v-error :error="errors.ciudad"></v-error>
                            </div>
                            <div class="col mb-2">
                                <label
                                    class="text-capitalize fw-bold"
                                    for="direccion"
                                    >direccion</label
                                >
                                <input
                                    @keypress.enter="update(user)"
                                    v-model="user.direccion"
                                    type="text"
                                    name="direccion"
                                    class="form-control-sm form-control"
                                />
                                <v-error :error="errors.direccion"></v-error>
                            </div>
                            <div class="col mb-2">
                                <label
                                    class="text-capitalize fw-bold"
                                    for="nacimiento"
                                    >nacimiento</label
                                >
                                <input
                                    @keypress.enter="update(user)"
                                    v-model="user.nacimiento"
                                    type="date"
                                    name="nacimiento"
                                    class="form-control-sm form-control"
                                />
                                <v-error :error="errors.nacimiento"></v-error>
                            </div>
                            <div class="col mb-2">
                                <label
                                    class="text-capitalize fw-bold"
                                    for="registrado"
                                    >registrado</label
                                >
                                <p>{{ user.registrado }}</p>
                            </div>
                            <div class="col mb-2">
                                <label
                                    class="text-capitalize fw-bold"
                                    for="actualizado"
                                    >actualizado</label
                                >
                                <p>{{ user.actualizado }}</p>
                            </div>
                            <div class="col mb-2">
                                <label
                                    class="text-capitalize fw-bold"
                                    for="actualizado"
                                    >Deshabilitado</label
                                >
                                <p class="fw-bold text-primary">
                                    {{
                                        user.inactivo
                                            ? `Usuario inactivo desde ${user.inactivo}`
                                            : "Usuario Activo"
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-2" v-show="client == 0">
                        <div class="row row-cols-4 col-12">
                            <div
                                class="col-12 border-bottom h5 mb-4 border-top"
                            >
                                Permisos del usuario
                            </div>
                            <div
                                class="col form-check text-start my-1"
                                v-for="(item, index) in roles"
                            >
                                <input
                                    @click="addOrRemoveRoles(item.id)"
                                    class="form-check-input"
                                    :id="item.id"
                                    :value="item.id"
                                    type="checkbox"
                                />
                                <label class="form-check-label" :for="item.id">
                                    <span class="fw-bold"
                                        >{{ item.role }}:</span
                                    >
                                    {{ item.descripcion }}
                                </label>
                            </div>
                            <div
                                v-if="message"
                                class="col-12 mt-4 py-4 fw-bold bg-success"
                            >
                                <span class="text-light">{{ message }}</span>
                            </div>
                        </div>
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
            window
                .axios(item.links.roles)
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
