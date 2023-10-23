<template>
    <v-modal target="create" width="modal-xl" @is-accepted="createUser">
        <template v-slot:button> Registrar </template>
        <template v-slot:head> Registrar un nuevo usaurio </template>
        <template v-slot:body>
            <div class="row row-cols-1 col-sm-12">
                <div class="col">
                    <div class="row row-cols-2 col-sm-12">
                        <div class="col my-1">
                            <label for="nombre">Nombre</label>
                            <input
                                id="nombre"
                                placeholder="Nombre"
                                class="form-control"
                                type="text"
                                v-model="form.nombre"
                            />
                            <v-error :error="errors.nombre"></v-error>
                        </div>
                        <div class="col my-1">
                            <label for="apellido">Apellido</label>
                            <input
                                type="text"
                                id="apellido"
                                placeholder="Apellido"
                                v-model="form.apellido"
                                class="form-control"
                            />
                            <v-error :error="errors.apellido"></v-error>
                        </div>
                        <div class="col my-1">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                v-model="form.correo_electronico"
                                placeholder="email@admin.com "
                                class="form-control"
                            />
                            <v-error
                                :error="errors.correo_electronico"
                            ></v-error>
                        </div>
                        <div class="col my-1">
                            <label for="documento">Tipo de documento</label>
                            <select
                                name="documento"
                                id="documento"
                                v-model="form.documento"
                                class="form-select"
                            >
                                <option
                                    :value="item"
                                    v-for="(item, index) in documents"
                                    :key="index"
                                >
                                    {{ item }}
                                </option>
                            </select>
                            <v-error :error="errors.documento"></v-error>
                        </div>
                        <div class="col my-1">
                            <label for="numero">Numero de documento</label>
                            <input
                                type="text"
                                id="numero"
                                v-model="form.numero"
                                placeholder="Numero"
                                class="form-control"
                            />
                            <v-error :error="errors.numero"></v-error>
                        </div>
                        <div class="col my-1">
                            <label for="pais">pais</label>
                            <input
                                type="text"
                                id="pais"
                                placeholder="Pais"
                                v-model="form.pais"
                                class="form-control"
                            />
                            <v-error :error="errors.pais"></v-error>
                        </div>
                        <div class="col my-1">
                            <label for="departamento">Departamento</label>
                            <input
                                type="text"
                                id="departamento"
                                v-model="form.departamento"
                                placeholder="departamento"
                                class="form-control"
                            />
                            <v-error :error="errors.departamento"></v-error>
                        </div>
                        <div class="col my-1">
                            <label for="Direccion">Direccion</label>
                            <input
                                type="text"
                                id="Direccion"
                                v-model="form.direccion"
                                placeholder="Direccion"
                                class="form-control"
                            />
                            <v-error :error="errors.direccion"></v-error>
                        </div>
                        <div class="col my-1">
                            <label for="telefono">Telefono</label>
                            <input
                                type="text"
                                id="telefono"
                                v-model="form.telefono"
                                placeholder="telefono"
                                class="form-control"
                            />
                            <v-error :error="errors.telefono"></v-error>
                        </div>
                    </div>
                </div>
                <div class="col my-2">
                    <div class="row row-cols-3 cols-sm-12">
                        <div
                            class="col-12 text-center h5 border-bottom border-top"
                        >
                            <span class="">Permisos de usuario</span>
                        </div>
                        <div
                            class="col form-check"
                            v-for="(item, index) in roles"
                            :key="index"
                        >
                            <input
                                class="form-check-input"
                                type="checkbox"
                                :value="item.id"
                                :id="index"
                                v-model="form.acceso"
                            />
                            <label
                                class="form-check-label text-sm"
                                :for="index"
                            >
                                <strong>{{ item.role }}: </strong>
                                <span>{{ item.descripcion }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div>
                    <v-error :error="errors.acceso"></v-error>
                </div>
            </div>
        </template>
    </v-modal>
</template>
<script>
export default {

    emits:["userWasRegistered"],

    data() {
        return {
            documents: {},
            form: { acceso: [] },
            errors: {},
            roles: {},
        };
    },

    mounted() {
        this.getDocuments();
        this.getRoles();
    },

    methods: {
        getDocuments() {
            window.axios.get("api/document-type").then((res) => {
                this.documents = res.data;
            });
        },

        getRoles() {
            window.axios
                .get("/api/roles")
                .then((res) => {
                    this.roles = res.data.data;
                })
                .catch((e) => {});
        },

        createUser() {
            window.axios
                .post("/api/users", this.form)
                .then((res) => {
                    this.form = { acceso: [] };
                    this.errors = {};
                    this.$emit("userWasRegistered", res.data.data)
                })
                .catch((e) => {
                    if (e.response && e.response.data.errors) {
                        this.errors = e.response.data.errors;
                    }
                });
        },
    },
};
</script>

<style scoped lang="css">
label {
    color: white;
}
</style>
