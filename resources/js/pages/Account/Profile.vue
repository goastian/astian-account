<template>
    <div class="card text-color">
        <div class="card-head h3 fw-bold border-bottom px-3 bg-primary">
            About Me
            <span class="h5 text-primary">
                <i class="bi bi-person-check h2"></i>
            </span>
        </div>
        <div class="card-body">
            <span class="h5 name">
                <i class="bi bi-person-bounding-box text-primary"></i>
                {{ user.nombre }}
                {{ user.apellido }}
            </span>

            <span class="h5 email">
                <i class="bi bi-envelope-at-fill text-primary"></i>
                {{ user.correo }}
            </span>

            <span v-show="user.telefono" class="h5 phone">
                <i class="bi bi-telephone-plus text-primary"></i>
                {{ user.telefono }}
            </span>

            <span v-show="user.nacimiento" class="h5 birth">
                <i class="bi bi-cake2 text-primary"></i>
                {{ user.nacimiento }}
            </span>

            <span v-show="user.pais" class="h5 address">
                <i class="bi bi-houses text-primary"></i>
                {{ user.pais }} - {{ user.ciudad }} -

                {{ user.direccion }}
            </span>
        </div>
        <div class="card-footer align-content-between">
            <span class="h5">
                <i class="bi bi-arrow-through-heart text-primary"></i>
                Join us {{ user.registrado }}
            </span>

            <v-update
                styles="btn btn-link float-end"
                :user="user"
                @success="authenticated"
            ></v-update>
        </div>
    </div>

    <div class="card">
        <div class="card-head h4 fw-bold px-3 border-bottom bg-primary py-2">
            My Scopes <i class="bi bi-shield-lock h3 text-primary"></i>
        </div>
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

<style lang="scss" scoped>
.card {
    margin-bottom: 1%;
}

.name,
.address,
.email,
.phone,
.birth {
    margin-right: 4%;
    @media (min-width: 240px) {
        display: block;
    }

    @media (min-width: 800px) {
        display: inline;
    }
}

.address {
    display: block;
    margin: 2% 0%;
}
</style>
