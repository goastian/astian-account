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
            <div class="row">
                <div class="row row-cols-1 col-12">
                    <div class="col">
                        <div class="row row-cols-3 col-12">
                            <div class="col">
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
                                <span
                                    class="errors"
                                    v-for="(item, index) in errors.nombre"
                                    :key="index"
                                    >{{ errors.nombre }}</span
                                >
                            </div>
                            <div class="col">
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
                                <span
                                    class="errors"
                                    v-for="(item, index) in errors.apellido"
                                    :key="index"
                                    >{{ errors.apellido }}</span
                                >
                            </div>
                            <div class="col">
                                <label class="text-capitalize fw-bold" for=""
                                    >Correo</label
                                >
                                <input
                                    @keypress.enter="update(user)"
                                    v-model="user.correo_electronico"
                                    type="email"
                                    name="correo_electronico"
                                    class="form-control-sm form-control"
                                />
                                <span
                                    class="errors"
                                    v-for="(
                                        item, index
                                    ) in errors.correo_electronico"
                                    :key="index"
                                    >{{ errors.correo_electronico }}</span
                                >
                            </div>

                            <div class="col">
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
                                <span
                                    class="errors"
                                    v-for="(item, index) in errors.telefono"
                                    :key="index"
                                    >{{ errors.telefono }}</span
                                >
                            </div>

                            <div class="col">
                                <label
                                    class="text-capitalize fw-bold"
                                    for="documento"
                                    >documento</label
                                >
                                <select
                                    v-model="user.documento"
                                    class="form-select text-uppercase"
                                    aria-label=""
                                >
                                    <option
                                        v-for="(item, index) in documents"
                                        :key="index"
                                        :value="item"
                                    >
                                        {{ item }}
                                    </option>
                                </select>
                                <span
                                    class="errors"
                                    v-for="(item, index) in errors.documento"
                                    :key="index"
                                    >{{ errors.documento }}</span
                                >
                            </div>
                            <div class="col">
                                <label
                                    class="text-capitalize fw-bold"
                                    for="numero"
                                    >numero</label
                                >
                                <input
                                    @keypress.enter="update(user)"
                                    v-model="user.numero"
                                    type="text"
                                    name="numero"
                                    class="form-control-sm form-control"
                                />
                                <span
                                    class="errors"
                                    v-for="(item, index) in errors.numero"
                                    :key="index"
                                    >{{ errors.numero }}</span
                                >
                            </div>
                            <div class="col">
                                <label
                                    class="text-capitalize fw-bold"
                                    for="departamento"
                                    >departamento</label
                                >
                                <input
                                    @keypress.enter="update(user)"
                                    v-model="user.departamento"
                                    type="text"
                                    name="departamento"
                                    class="form-control-sm form-control"
                                />

                                <span
                                    class="errors"
                                    v-for="(item, index) in errors.departamento"
                                    :key="index"
                                    >{{ errors.departamento }}</span
                                >
                            </div>
                            <div class="col">
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
                                <span
                                    class="errors"
                                    v-for="(item, index) in errors.pais"
                                    :key="index"
                                    >{{ errors.pais }}</span
                                >
                            </div>
                            <div class="col">
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
                                <span
                                    class="errors"
                                    v-for="(item, index) in errors.direccion"
                                    :key="index"
                                    >{{ errors.direccion }}</span
                                >
                            </div>
                            <div class="col">
                                <label
                                    class="text-capitalize fw-bold"
                                    for="registrado"
                                    >registrado</label
                                >
                                <p>{{ user.registrado }}</p>
                            </div>
                            <div class="col">
                                <label
                                    class="text-capitalize fw-bold"
                                    for="actualizado"
                                    >actualizado</label
                                >
                                <p>{{ user.actualizado }}</p>
                            </div>
                            <div class="col">
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
                    <div class="col">
                        <div class="row row-cols-3 col-12">
                            <div
                                class="col-12 border-bottom h5 mb-4 border-top"
                            >
                                Permisos del usuario
                            </div>
                            <div
                                class="col text-start"
                                v-for="(item, index) in roles"
                            >
                                <input
                                    @click="addOrRemoveRoles(item.role)"
                                    class="form-check-input mx-2"
                                    :id="item.role"
                                    :value="item.id"
                                    type="checkbox"
                                />
                                <label
                                    class="text-capitalize fw-bold"
                                    :for="item.role"
                                >
                                    <strong>{{ item.role }}:</strong>
                                    <span> {{ item.descripcion }} </span></label
                                >
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
    
    emits:['userWasUpdated'],

    props: {
        user: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            message: null,
            status: false,
            errors: {},
            roles: {},
            documents: {},
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
            const value = document.getElementById(id).value;
            if (checked) {
                window.axios
                    .post(this.user.links.roles, { role: value })
                    .then((res) => {
                        this.message = `Se asigno un nuevo role ${res.data.data.role} ha sido asignado`;
                    })
                    .catch((e) => {
                        if (e.response && e.response.data.data.status == 403) {
                            this.message = e.response.data.data.message;
                        }
                    });
            } else {
                window.axios
                    .delete(`${this.user.links.roles}/${value}`)
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
            this.getUserRoles(user);
            this.getRoles();
            this.getDocuments();
        },

        getRoles() {
            window.axios
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
            window.axios
                .put(item.links.update, this.user)
                .then((res) => {
                    this.status = "Usuario Actualizado";
                    this.$emit('userWasUpdated', res.data.data)
                })
                .catch((e) => {
                    if (e.response && e.response.data.errors) {
                        this.errors = e.response.data.errors;
                    }
                });
        },

        getDocuments() {
            window.axios.get("/api/document-type").then((res) => {
                this.documents = res.data;
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
