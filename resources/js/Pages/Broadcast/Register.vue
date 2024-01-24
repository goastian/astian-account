<template>
    <div class="card mb-2 border-bottom">
        <div class="card-head">Registrar nuevo canal</div>
        <div class="card-body">
            <div class="row broadcast-register">
                <div class="col">
                    <input
                        v-model="form.canal"
                        type="text"
                        class="form-control"
                        placeholder="Canal"
                    />
                    <v-error :error="errors.canal"></v-error>
                </div>
                <div class="col">
                    <input
                        v-model="form.descripcion"
                        class="form-control"
                        placeholder="Descripcion"
                    />

                    <v-error :error="errors.descripcion"></v-error>
                </div>
                <div class="col">
                    <button class="btn btn-success" @click="storeBroadcast">
                        Agregar
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
            form: {
                descripcion: "",
                canal: "",
            },
            errors: {},
        };
    },

    methods: {
        storeBroadcast() {
            this.$server
                .post("/api/broadcasts", this.form)
                .then((res) => {
                    this.form.canal = "";
                    this.form.descripcion = "";
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
<style lang=""></style>
