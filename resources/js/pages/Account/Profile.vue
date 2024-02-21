<template>
    <div class="row profile">
        <div class="col">
            <div class="card about">
                <div class="card-head">Acerca de</div>
                <div class="card-body">
                    <p>
                        <span>Nombre: </span>{{ user.nombre }}
                        {{ user.apellido }}
                    </p>

                    <p><span>Apellido: </span> {{ user.correo }}</p>
                    <p><span>Miembro desde: </span>{{ user.registrado }}</p>

                    <p>
                        <v-update
                            styles="btn px-0 mx-0 btn-link"
                            :user="user"
                            @success="authenticated"
                        ></v-update>
                    </p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card roles">
                <div class="card-head">Roles</div>
                <div class="card-body">
                    <a
                        href="#"
                        class="btn btn-link"
                        v-for="(item, index) in roles"
                        :key="index"
                        >{{ item.id }}</a
                    >
                </div>
            </div>
        </div>
        <div class="col-12"></div>
        <div class="col"></div>
    </div>

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
        this.scopes();
    },

    methods: {
        authenticated() {
            this.$server
                .get("/api/gateway/user")
                .then((res) => {
                    this.user = res.data;
                    window.$auth = res.data;
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

        scopes() {
            this.$server
                .get("/api/oauth/scopes")
                .then((res) => {
                    this.roles = res.data;
                })
                .catch((e) => {
                    console.log(e);
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
