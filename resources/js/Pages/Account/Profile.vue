<template>
    <div class="card profile">
        <div class="row">
            <div class="head">
                <img src="../../../img/favicon.svg" alt="user image" />
            </div>
            <div class="body">
                <div
                    class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4"
                >
                    <div class="profile-info">
                        <h4 class="text-capitalize">
                            {{ user.nombre }} {{ user.apellido }}
                        </h4>
                        <ul
                            class="list-inline mb-0 align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2"
                        >
                            <li class="list-inline-item">
                                <i
                                    class="mdi mdi-invert-colors me-1 mdi-20px"
                                ></i
                                ><span class="fw-medium">{{
                                    user.telefono
                                }}</span>
                            </li>
                            <li class="list-inline-item">
                                <i
                                    class="mdi mdi-map-marker-outline me-1 mdi-20px"
                                ></i
                                ><span class="fw-medium">{{
                                    user.correo
                                }}</span>
                            </li>
                            <li class="list-inline-item">
                                <i
                                    class="mdi mdi-calendar-blank-outline me-1 mdi-20px"
                                ></i
                                ><span class="fw-medium">
                                    Joined {{ user.registrado }}</span
                                >
                            </li>
                        </ul>
                    </div>

                    <v-update
                        styles="btn btn-success"
                        :user="user"
                        @success="authenticated"
                    ></v-update>
                </div>
            </div>
        </div>
    </div>

    <!--  <div class="row profile">
        <div class="col photo">
            <div class="card">
                <div class="card-head"></div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <img
                                src=".././../../img/favicon.svg"
                                alt="profile image"
                            />
                        </li>
                        <li class="list-group-item text-capitalize">
                            <strong>
                                
                            </strong>
                        </li>
                        <li class="list-group-item">{{ user.telefono }}</li>
                        <li class="list-group-item">{{ user.correo }}</li>
                        <li class="list-group-item">
                            Desde {{ user.registrado }}
                        </li>
                        <v-update
                            :user="user"
                            @success="authenticated"
                        ></v-update>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col info">
            <div class="card">
                <div class="card-head">Informacion</div>
                <div class="card-body">
                    <ul class="list-group">
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
                            <strong
                                >TOTP - Time-based One-Time Passwords :
                            </strong>
                            No disponible
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col roles">
            <div class="card">
                <div class="card-head">Roles</div>
                <div class="card-body">
                    <span
                        class="line"
                        v-for="(item, index) in roles"
                        :key="index"
                    >
                        {{ item.role }}</span
                    >
                </div>
            </div>
        </div>
    </div>-->

    <v-message :message="message" @close="close"></v-message>
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
            message: null,
            sessions: {},
        };
    },

    created() {
        this.authenticated();
    },

    methods: {
        authenticated() {
            this.$server
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

        scopes(link) {
            this.$server
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
