<template>
    <div class="container-fluid">
        <div class="row row-cols-2 col-sm-12">
            <div class="col-4 px-0">
                <div class="card">
                    <div class="card-head text-center rounded border py-5">
                        photo
                    </div>
                    <div class="card-body text-center">
                        <ul class="list-group">
                            <li class="list-group-item text-capitalize">
                                <strong>
                                    {{ user.nombre }} {{ user.apellido }}
                                </strong>
                            </li>
                            <v-update
                                :user="user"
                                @success="authenticated"
                            ></v-update>

                            <li class="list-group-item">{{ user.telefono }}</li>
                            <li class="list-group-item">{{ user.correo }}</li>

                            <v-remove
                                :user="user"
                                @success="showMessage"
                                @errors = "showMessage"
                            ></v-remove>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <ul class="list-group">
                    <li class="list-group-item active text-center">
                        <strong> Datos </strong>
                    </li>
                    <li class="list-group-item">
                        <strong> Desde : </strong>{{ user.registrado }}
                    </li>
                    <li class="list-group-item">
                        <strong> Ultima Actualizacion : </strong
                        >{{ user.actualizado }}
                    </li>
                    <li class="list-group-item">
                        <strong> Pais : </strong>{{ user.pais }}
                    </li>
                    <li class="list-group-item">
                        <strong> Ciudad: </strong>{{ user.ciudad }}
                    </li>
                    <li class="list-group-item">
                        <strong> Direccion: </strong>{{ user.direccion }}
                    </li>
                    <li class="list-group-item">
                        <strong> Nacimiento: </strong>{{ user.nacimiento }}
                    </li>
                    <li
                        :class="[
                            'list-group-item',
                            user.totp ? 'text-success' : 'text-danger',
                        ]"
                    >
                        <strong> 2FA - Two Factor Authentication: </strong>
                        <span>
                            {{ user.m2fa ? "Activado" : "Inactivo" }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <strong>TOTP - Time-based One-Time Passwords : </strong>
                        No disponible
                    </li>
                </ul>
            </div>
            <div class="col-12 my-4 px-0">
                <ul class="list-group">
                    <li class="list-group-item active text-center">
                        Permisos del ususario
                    </li>
                    <li class="list-group-item">
                        <span
                            class="line"
                            v-for="(item, index) in roles"
                            :key="index"
                        >
                            {{ item.role }}</span
                        >
                    </li>
                </ul>
            </div>

            <v-message :message="message"  @message-send="close"></v-message>

            <div class="col-12 px-0">
                <ul class="list-group">
                    <li class="list-group-item active text-center">
                        Dispositivos conectados {{ sessions.length }}
                    </li>
                    <li class="list-group-item">
                        <button
                            class="btn-secondary btn mx-1 mb-1"
                            v-for="(item, index) in sessions"
                            :key="index"
                        >
                            <span style="display: inline-block">
                                {{ item.ip }} <br />
                                {{ item.agente }} <br />
                                {{ item.ultima_coneccion }}
                            </span>
                            <a
                                @click="destroySession(item.links.destroy)"
                                class="btn btn-danger text-white"
                                >X</a
                            >
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
import VUpdate from "../Users/Update.vue";
import VRemove from "./Remove.vue";

export default {
    components: {
        VUpdate,
        VRemove,
    },

    data() {
        return {
            user: {},
            roles: {},
            message: null,
            sessions: {},
        };
    },

    created() {
        this.authenticated();
        this.session();
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

        close() {
            this.message = null;
        },

        showMessage(event) {
            if (!event.status) {
                this.message = event;
                setTimeout(() => {
                    window.location.href = window.location.host;
                }, 5000);
            }
            this.message = event.data.message;
        },

        session() {
            window.axios
                .get("/api/sessions")
                .then((res) => {
                    this.sessions = res.data.data;
                })
                .catch((e) => {
                    if (e.response) {
                        console.log(e.response);
                    }
                });
        },

        destroySession(link) {
            window.axios
                .delete(link)
                .then((res) => {
                    this.session();
                })
                .catch((e) => {
                    if (e.response) {
                        console.log(e.response);
                    }
                });
        },

        scopes(link) {
            window.axios
                .get(link)
                .then((res) => {
                    this.roles = res.data.data;
                })
                .catch((e) => {
                    if (e.response) {
                        console.log(e.response);
                    }
                });
        },

        listener() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("UpdateEmployeeEvent", (e) => {
                    this.authenticated();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("StoreEmployeeRoleEvent", (e) => {
                    this.authenticated();
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyEmployeeRoleEvent", (e) => {
                    this.authenticated();
                });

            this.$echo
                .private(this.$channels.ch_1(window.$auth.id))
                .listen("authorize", (e) => {
                    this.session();
                });
        },
    },
};
</script>
<style lang="css" scoped>
.line {
    display: inline-block;
    margin: 0.5%;
    padding: 0.5%;
    border-radius: 5%;
    background-color: aqua;
}

</style>
