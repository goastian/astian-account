<template>
    <div class="container-fluid text-center">
        <div class="row row-cols-6 col-sm-12 mb-3">
            <div class="col">
                <input
                    type="text"
                    placeholder="Nombre"
                    name="nombre"
                    id="nombre"
                    v-model="search.nombre"
                    class="form-control form-control-sm"
                    @keyup.enter="searching()"
                />
            </div>
            <div class="col">
                <input
                    type="text"
                    placeholder="Apellido"
                    name="apellido"
                    id="apellido"
                    v-model="search.apellido"
                    class="form-control form-control-sm"
                    @keyup.enter="searching()"
                />
            </div>

            <div class="col">
                <input
                    type="email"
                    placeholder="Email"
                    name="email"
                    id="email"
                    v-model="search.correo"
                    class="form-control form-control-sm"
                    @keyup.enter="searching()"
                />
            </div>
            <div class="col">
                <input
                    type="text"
                    placeholder="Documento"
                    name="numero"
                    id="numero"
                    v-model="search.numero"
                    class="form-control form-control-sm"
                    @keyup.enter="searching()"
                />
            </div>
            <div class="col">
                <button class="btn bg-warning btn-sm" @click="clear()">
                    Clear
                </button>
            </div>
            <div class="col">
                <slot name="button"></slot>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    emits: ["searching"],

    data() {
        return {
            search: {
                nombre: null,
                apellido: null,
                correo: null,
                numero: null,
            },
        };
    },

    methods: {
        searching() {
            this.$emit("searching", this.search);
        },

        clear() {
            (this.search = {
                nombre: null,
                apellido: null,
                correo: null,
                numero: null,
            }),
                this.$emit("searching", this.search);
        },
    },
};
</script>
<style lang=""></style>
