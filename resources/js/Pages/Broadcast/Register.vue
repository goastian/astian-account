<template>
    <div class="card bg-dark text-light mb-2 border-bottom" style="width: 100%; margin: auto">
        <div class="card-head text-center fw-bold">Registrar nuevo canal</div>
        <div class="card-body">
            <div class="row row-cols-3 col-12">
                <div class="col-3">
                    <input
                        v-model="form.canal"
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="Canal"
                    />
                    <v-error :error="errors.canal"></v-error>
                </div>
                <div class="col-7">
                    <input
                        v-model="form.descripcion"
                        class="form-control form-control-sm"
                        placeholder="Descripcion"
                    />

                    <v-error :error="errors.descripcion"></v-error>
                </div>
                <div class="col-1">
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
    emits: ["broadcastWasCreated"],

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
                    this.$emit("broadcastWasCreated", res.data.data);
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
