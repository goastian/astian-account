<template>
    <div class="card p-2">
        <div class="card-head">Registar nuevo scope</div>
        <div class="card-body align-content-lg-stretch">
            <div class="row role-register">
                <div class="col">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Role"
                        v-model="form.role"
                    />
                    <v-error :error="errors.role"></v-error>
                </div>
                <div class="col">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Descripcion"
                        v-model="form.descripcion"
                    />
                    <v-error :error="errors.descripcion"></v-error>
                </div>
                <div class="col">
                    <button class="btn btn-sm btn-primary" @click="create">
                        registrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    emits: ["success"],

    data() {
        return {
            errors: {},
            form: {},
        };
    },

    methods: {
        create() {
            this.$server
                .post("/api/roles", this.form)
                .then((res) => {
                    this.errors = {};
                    this.form = {};
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
