<template>
    <v-modal target="create" @is-accepted="createUser">
        <template v-slot:button> New user </template>
        <template v-slot:body>
            <div class="row user-register">
                <div class="col">
                    <input
                        placeholder="Firs Name"
                        class="form-control form-control-sm"
                        type="text"
                        v-model="form.nombre"
                    />
                    <v-error :error="errors.nombre"></v-error>
                </div>
                <div class="col">
                    <input
                        type="text"
                        placeholder="Last Name"
                        v-model="form.apellido"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.apellido"></v-error>
                </div>
                <div class="col">
                    <input
                        type="email"
                        v-model="form.correo"
                        placeholder="Email Address"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.correo"></v-error>
                </div>
                <div class="col">
                    <input
                        type="text"
                        id="pais"
                        placeholder="Country"
                        v-model="form.pais"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.pais"></v-error>
                </div>
                <div class="col">
                    <input
                        type="text"
                        v-model="form.ciudad"
                        placeholder="City"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.ciudad"></v-error>
                </div>
                <div class="col">
                    <input
                        type="text"
                        v-model="form.direccion"
                        placeholder="Home Address"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.direccion"></v-error>
                </div>
                <div class="col">
                    <input
                        type="text"
                        v-model="form.telefono"
                        placeholder="Phone Number"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.telefono"></v-error>
                </div>
                <div class="col">
                    <input
                        type="date"
                        v-model="form.nacimiento"
                        placeholder="Birthday"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.nacimiento"></v-error>
                </div>
            </div>
            <div class="m-2 p-2">
                <span class="">User Scopes</span>
            </div>
            <div class="row user-scopes border p-1">
                <div
                    class="col form-check"
                    v-for="(item, index) in roles"
                    :key="index"
                    v-show="!item.publico"
                >
                    <input
                        class="form-check-input"
                        type="checkbox"
                        :value="item.id"
                        :id="index"
                        v-model="form.acceso"
                    />
                    <label class="form-check-label text-sm" :for="index">
                        <strong class="text-color">{{ item.role }}: </strong>
                        <span>{{ item.descripcion }}</span>
                    </label>
                </div>
            </div>
            <div>
                <v-error :error="errors.acceso"></v-error>
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

<style lang="scss" scoped>
.col {
    flex: 0 0 auto;
    
    @media (min-width: 320px) {
        margin-bottom: 2%;
        width: 98%;
    }
    
    @media (min-width: 800px) {
        width: 45%;
    }
    
    @media (min-width: 940px) {
        margin-bottom: 1%;
        width: 30%;
    }
}


</style>
