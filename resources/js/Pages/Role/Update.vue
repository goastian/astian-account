<template>
    <v-modal
        :target="role.role.trim()"
        @is-clicked="loadData(role)"
        @is-accepted="update(form)"
        @is-closed="clean"
    >
        <template v-slot:button> Actualizar </template>
        <template v-slot:head> Actualizar datos </template>
        <template v-slot:body>
            <div class="row update-role">
                <div class="col">
                    <label for="role">Role</label>
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Role"
                        id="role"
                        v-model="form.role"
                    />
                    <v-error :error="errors.role"></v-error>
                </div>
                <div class="col">
                    <label for="descripcion">Descripcion</label>
                    <input
                        type="text"
                        class="form-control"
                        placeholder="descripcion"
                        v-model="form.descripcion"
                    />
                    <v-error :error="errors.descripcion"></v-error>
                </div>
                <div class="col">
                    <label for="public">Publico</label>
                    <select
                        v-model="form.publico"
                        name="publico"
                        id="publico"
                        class="form-control"
                    >
                        <option value="1">Si</option>
                        <option value="0">No</option>
                    </select>
                    <v-error :error="errors.publico"></v-error>
                </div>
            </div>
            <div v-show="message" class="my-3 py-2 bg-secondary text-light h6">
                {{ message }}
            </div>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["success"],

    props: ["role"],

    data() {
        return {
            errors: {},
            message: null,
            form: {
                role: null,
                descripcion: null,
                publico: 0,
            },
        };
    },

    methods: {
        clean() {
            this.errors = {};
            this.form = {};
            this.message = null;
        },

        loadData(role) {
            this.$server
                .get(role.links.show)
                .then((res) => {
                    this.form = res.data.data;
                })
                .catch((e) => {
                    console.log(e);
                });
        },

        update(role) {
            this.$server
                .put(role.links.update, this.form)
                .then((res) => {
                    this.message = "actualizacion exitosa";
                    this.errors = {};
                    this.$emit("success", res.data.data);
                })
                .catch((e) => {
                    if (
                        e.response &&
                        e.response.status != 403 &&
                        e.response.data.errors
                    ) {
                        this.errors = e.response.data.errors;
                    }

                    if (e.response.status == 403 && e.response.data.message) {
                        this.message = e.response.data.message;
                    }
                });
        },
    },
};
</script>
