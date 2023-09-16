<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-head h3 text-center">Iniciar Session</div>
            <div class="card-body text-center py-3">
                <div class="row row-cols-1 col-12 text-center">
                    <div class="col">
                        <label for="email">Correo electronico</label>
                        <input
                            type="email"
                            placeholder="email@admin.com"
                            id="email"
                            name="email"
                            v-model="user.correo_electronico"
                            class="form-control py-2 text-center"
                        />
                        <span
                            class="errors"
                            v-for="(item, index) in errors.correo_electronico"
                            :key="index"
                            >{{ item }}</span
                        >
                    </div>
                    <div class="col my-3">
                        <label for="password">Contraseña</label>
                        <input
                            type="password"
                            placeholder="password"
                            id="contraseña"
                            name="contraseña"
                            v-model="user.contraseña"
                            class="form-control py-2 text-center"
                        />
                        <span
                            class="errors"
                            v-for="(item, index) in errors.contraseña"
                            :key="index"
                            >{{ item }}</span
                        >
                    </div>
                </div>
                <div class="d-block mt-3">
                    <button class="btn btn-success" @click="signin(login)">
                        Ingresar
                    </button>
                    <br />
                    <a class="btn btn-link my-2" :href="reset_password"
                        >Recuperar mi contraseña</a
                    >
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Cookies from "js-cookie";

export default {
    props: {
        reset_password: {
            required: true,
            type: String,
        },
        domain: {
            required: true,
            type: String,
        },
        login: {
            required: true,
            type: String,
        },
        frontend: {
            required: true,
            type: String,
        },
    },

    data() {
        return {
            errors: {},
            user: {},
        };
    },

    methods: {
        signin(login) {
            window.axios
                .post(login, this.user)
                .then((res) => {
                    Cookies.set("spondylus", res.data.data.Authorization, {
                        path: "/",
                        domain: ".spondylus.xyz",
                    });
                    window.location.href = this.frontend;
                })
                .catch((e) => {
                    console.log(e.response.data.errors);
                    if (e.response && e.response.data.errors) {
                        this.errors = e.response.data.errors;
                    }
                });
        },
    },
};
</script>
<style lang="css">
.card {
    width: 40%;
    margin: 5% auto;
}
</style>
