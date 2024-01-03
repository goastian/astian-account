<template>
    <div class="container-fluid">
        <div class="row row-cols-2 col-sm-12">
            <v-update :user="user" @user-was-updated="authenticated"></v-update>
            <div class="col mb-3">
                <span class="d-block fw-bold text-capitalize text-secondary"
                    >nombre</span
                >
                <span>{{ user.nombre }}</span>
            </div>
            <div class="col mb-3">
                <span class="d-block fw-bold text-capitalize text-secondary"
                    >apellido</span
                >
                <span class="font-monospace">
                    {{ user.apellido }}
                </span>
            </div>
            <div class="col mb-3">
                <span class="d-block fw-bold text-capitalize text-secondary"
                    >email</span
                >
                <span class="font-monospace">
                    {{ user.correo }}
                </span>
            </div>
            <div class="col mb-3">
                <span class="d-block fw-bold text-capitalize text-secondary"
                    >documento</span
                >
                <span class="font-monospace">
                    {{ user.documento }}
                </span>
            </div>
            <div class="col mb-3">
                <span class="d-block fw-bold text-capitalize text-secondary"
                    >numero</span
                >
                <span class="font-monospace">
                    {{ user.numero }}
                </span>
            </div>
            <div class="col mb-3">
                <span class="d-block fw-bold text-capitalize text-secondary"
                    >pais</span
                >
                <span class="font-monospace">
                    {{ user.pais }}
                </span>
            </div>
            <div class="col mb-3">
                <span class="d-block fw-bold text-capitalize text-secondary"
                    >ciudad</span
                >
                <span class="font-monospace">
                    {{ user.departamento }}
                </span>
            </div>
            <div class="col mb-3">
                <span class="d-block fw-bold text-capitalize text-secondary"
                    >direccion</span
                >
                <span class="font-monospace">
                    {{ user.direccion }}
                </span>
            </div>
            <div class="col mb-3">
                <span class="d-block fw-bold text-capitalize text-secondary"
                    >telefono</span
                >
                <span class="font-monospace">
                    {{ user.telefono }}
                </span>
            </div>
            <div class="col mb-3">
                <span class="d-block fw-bold text-capitalize text-secondary"
                    >2FA - Two Factor Authentication</span
                >
                <span
                    :class="[
                        'font-monospace',
                        user.m2fa ? 'text-success' : 'text-danger',
                    ]"
                >
                    {{ user.m2fa ? "Activado" : "Inactivo" }}
                </span>
            </div>
            <div class="col mb-3">
                <span class="d-block fw-bold text-capitalize text-secondary"
                    >registrado</span
                >
                <span class="font-monospace">
                    {{ user.registrado }}
                </span>
            </div>
            <div class="col mb-3">
                <span class="d-block fw-bold text-capitalize text-secondary"
                    >actualizado</span
                >
                <span class="font-monospace">
                    {{ user.actualizado }}
                </span>
            </div>
            <div>
                <span class="d-block fw-bold text-capitalize text-secondary"
                    >Roles Asignados</span
                >
                <ul v-for="(item, index) in roles" :key="index">
                    <li>
                        {{ item.role }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
import VUpdate from "../Users/Update.vue";

export default {
    components: {
        VUpdate,
    },

    data() {
        return {
            user: {},
            roles: {},
        };
    },

    created() {
        this.authenticated();
        this.listener();
    },

    methods: {
        authenticated() {
            window.axios
                .get("/api/gateway/user")
                .then((res) => {
                    this.user = res.data;
                    window.$auth = res.data;
                    this.scopes(res.data.links.roles);
                })
                .catch((e) => {
                    console.log(e);
                });
        },

        scopes(link) {
            window.axios
                .get(link)
                .then((res) => {
                    this.roles = res.data.data;
                })
                .catch((e) => {
                    console.log(e);
                });
        },

        listener() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("UpdateEmployeeEvent", (e) => {
                    this.authenticated();
                });
        },
    },
};
</script>
<style lang=""></style>
