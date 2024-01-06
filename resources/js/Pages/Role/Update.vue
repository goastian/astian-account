<template>
    <v-modal :target="role.role.trim()" @is-accepted="update(role)">
        <template v-slot:button> Actualizar </template>
        <template v-slot:head> Actualizar datos </template>
        <template v-slot:body>
            <div class="row row-cols-3 col-12 mx-2 my-2 py-2 px-2">
                <div class="col-5">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="Role"
                        v-model="role.role"
                        @keyup.enter="update(role)"
                    />
                    <v-error :error="errors.role"></v-error>
                </div>
                <div class="col-5">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="descripcion"
                        v-model="role.descripcion"
                        @keyup.enter="update(role)"
                    />
                    <v-error :error="errors.descripcion"></v-error>
                </div>
            </div>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["success", 'errors'],

    props: {
        role: { type: Object, required: true },
    },

    data() {
        return {
            errors: {},
        };
    },

    methods: {
        update(role) {
            window.axios
                .put(role.links.update, this.role)
                .then((res) => {
                    this.errors = {};
                    this.$emit("success", res.data.data);
                })
                .catch((e) => {
                    if (e.response && e.response.status != 403 && e.response.data.errors) {
                        this.errors = e.response.data.errors;
                    }

                    if (e.response.status == 403) {
                        this.$emit('errors', e.response)
                    }
                });
        },
    },
};
</script>
<style lang=""></style>
