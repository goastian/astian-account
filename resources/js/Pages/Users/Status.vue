<template lang="">
    <v-modal
        :target="'__X_' + user.id"
        :styles="['btn-sm', [user.inactivo ? 'btn-danger' : 'btn-primary']]"
        :button_accept_show="false"
        button_cancel_name="Cancelar operacion"
    >
        <template v-slot:button>
            {{ user.inactivo ? "Inactivo" : "Activo" }}
        </template>
        <template v-slot:head>
            {{ user.inactivo ? "Habilitar usuario" : "Deshabilitar usuario" }}
        </template>

        <template v-slot:body>
            <div>
                {{
                    user.inactivo
                        ? "Estas seguro que deseas habilitar este usuario?"
                        : "Estas seguro que deseas deshabilitar a este usuario? por seguridad cuando realice esta accion se revocaran todos los tokens que el usuario haya generado."
                }}
            </div>
            <button
                @click="enableOrDisable(user)"
                data-bs-dismiss="modal"
                class="btn btn-success mt-4"
            >
                Aceptar
            </button>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["userStatus"],

    props: {
        user: { type: Object, requered: true },
    },

    methods: {
        
        enableOrDisable(item) {
            if (item.inactivo) {
                window.axios
                    .get(item.links.enable)
                    .then((res) => {
                        this.$emit("userStatus", res.data.data);
                    })
                    .catch((e) => {});
            } else {
                window.axios
                    .delete(item.links.disable)
                    .then((res) => {
                        this.$emit("userStatus", res.data.data);
                    })
                    .catch((e) => {});
            }
        },
    },
};
</script>
<style lang=""></style>
