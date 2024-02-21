<template>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <input
                        v-model="form.canal"
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="Channel"
                    />
                    <v-error :error="errors.canal"></v-error>
                </div>
                <div class="col">
                    <input
                        v-model="form.descripcion"
                        class="form-control form-control-sm"
                        placeholder="Description"
                    />

                    <v-error :error="errors.descripcion"></v-error>
                </div>
                <div class="col">
                    <button
                        class="btn btn-primary btn-sm"
                        @click="storeBroadcast"
                    >
                        Add new channel
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
<style lang="scss" scoped>
.col {
    flex: 0 0 auto;
    width: 90%;

    @media (min-width: 240px) {
        width: 98%;
        margin: 1% 0;
        padding: 0;
    }

    @media (min-width: 850px) {
        width: 48%;
        margin: 1%;
    }
}
</style>
