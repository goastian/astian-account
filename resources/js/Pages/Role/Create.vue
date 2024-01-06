<template>
    <div class="card bg-dark text-light w-50" style="margin: auto">
        <div class="head text-center py-2">Registar nuevo scope</div>
        <div class="body align-content-lg-stretch">
            <div class="row row-cols-3 col-12 mx-2 my-2 py-2 px-2">
                <div class="col-5">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="Role"
                        v-model="form.role"
                    />
                    <v-error :error="errors.role"></v-error>
                </div>
                <div class="col-5">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="descripcion"
                        v-model="form.descripcion"
                    />
                    <v-error :error="errors.descripcion"></v-error>
                </div>
                <div class="col-2">
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
            window.axios
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
