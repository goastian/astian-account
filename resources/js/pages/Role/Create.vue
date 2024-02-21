<template>
    <div class="card">
        <div class="card-body">
            <div class="row role-register">
                <div class="col">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="New scope Or Role"
                        v-model="form.role"
                    />
                    <v-error :error="errors.role"></v-error>
                </div>
                <div class="col">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="No lengthy description"
                        v-model="form.descripcion"
                    />
                    <v-error :error="errors.descripcion"></v-error>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            value="1"
                            id="categoria"
                            v-model="form.publico"
                        />
                        <label class="form-check-label text-color" for="categoria">
                            Make available for all users (Public Scope)
                        </label>
                    </div>
                </div>
                <div class="col">
                    <button class="btn btn-sm btn-primary" @click="create">
                        Add new Scope
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

<style lang="scss" scoped>
.col {
    flex: 0 0 auto;
    margin: 1%;

    @media (min-width:800px) {
        width: 30%;
    }
}
 
</style>
