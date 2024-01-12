<template>
    <div class="card bg-dark text-light my-3" style="width: 50%; margin: auto">
        <div class="card-head text-center fw-bold">Registrar nuevo canal</div>
        <div class="card-body">
            <div class="row row-cols-1 col-12">
                <div class="col">
                    <input
                        v-model="form.canal"
                        type="text"
                        class="form-control"
                        placeholder="Canal"
                    />
                    <v-error :error="errors.canal"></v-error>
                </div>
                <div class="col py-2">
                    <textarea
                        v-model="form.descripcion"
                        class="form-control"
                        placeholder="Descripcion"
                    ></textarea>

                    <v-error :error="errors.descripcion"></v-error>
                </div>
                <div class="col text-center">
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
                    this.$emit('broadcastWasCreated', res.data.data)
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
