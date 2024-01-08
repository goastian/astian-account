<template>
    <v-modal target="create" width="modal-xl" @is-accepted="createUser">
        <template v-slot:button> Registrar </template>
        <template v-slot:head> Registrar un nuevo usaurio </template>
        <template v-slot:body>
            <div class="row row-cols-1 col-sm-12">
                <div class="col">
                    <div class="row row-cols-3 col-sm-12">
                        <div class="col my-1">
                            <label for="nombre">nombre</label>
                            <input
                                id="nombre"
                                placeholder="nombre"
                                class="form-control form-control-sm py-0 my-0"
                                type="text"
                                v-model="form.nombre"
                            />
                            <v-error :error="errors.nombre"></v-error>
                        </div>
                        <div class="col my-1">
                            <label for="apellido">apellido</label>
                            <input
                                type="text"
                                id="apellido"
                                placeholder="apellido"
                                v-model="form.apellido"
                                class="form-control form-control-sm py-0 my-0"
                            />
                            <v-error :error="errors.apellido"></v-error>
                        </div>
                        <div class="col my-1">
                            <label for="email">email</label>
                            <input
                                type="email"
                                id="email"
                                v-model="form.correo"
                                placeholder="email@admin.com "
                                class="form-control form-control-sm py-0 my-0"
                            />
                            <v-error :error="errors.correo"></v-error>
                        </div>
                        <div class="col my-1">
                            <label for="pais">pais</label>
                            <input
                                type="text"
                                id="pais"
                                placeholder="Pais"
                                v-model="form.pais"
                                class="form-control form-control-sm py-0 my-0"
                            />
                            <v-error :error="errors.pais"></v-error>
                        </div>
                        <div class="col my-1">
                            <label for="ciudad">ciudad</label>
                            <input
                                type="text"
                                id="ciudad"
                                v-model="form.ciudad"
                                placeholder="ciudad"
                                class="form-control form-control-sm py-0 my-0"
                            />
                            <v-error :error="errors.ciudad"></v-error>
                        </div>
                        <div class="col my-1">
                            <label for="direccion">direccion</label>
                            <input
                                type="text"
                                id="direccion"
                                v-model="form.direccion"
                                placeholder="direccion"
                                class="form-control form-control-sm py-0 my-0"
                            />
                            <v-error :error="errors.direccion"></v-error>
                        </div>
                        <div class="col my-1">
                            <label for="telefono">telefono</label>
                            <input
                                type="text"
                                id="telefono"
                                v-model="form.telefono"
                                placeholder="telefono"
                                class="form-control form-control-sm py-0 my-0"
                            />
                            <v-error :error="errors.telefono"></v-error>
                        </div>
                        <div class="col my-1">
                            <label for="nacimiento">nacimiento</label>
                            <input
                                type="date"
                                id="nacimiento"
                                v-model="form.nacimiento"
                                placeholder="nacimiento"
                                class="form-control form-control-sm py-0 my-0"
                            />
                            <v-error :error="errors.nacimiento"></v-error>
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
                            class="col form-check text-start"
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
    emits: ["success", "errors"],

    data() {
        return { 
            form: { acceso: [] },
            errors: {},
            roles: {},
        };
    },

    mounted() {
        this.getRoles();
    },

    methods: {
        getRoles() {
            this.$server
                .get("/api/roles")
                .then((res) => {
                    this.roles = res.data.data;
                })
                .catch((e) => {});
        },

        createUser() {
            this.$server
                .post("/api/users", this.form)
                .then((res) => {
                    this.form = { acceso: [] };
                    this.errors = {};
                    this.$emit("success", res.data.data);
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
