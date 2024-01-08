<template>
    <nav class="navbar navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <router-link class="navbar-brand" :to="{ name: 'home.about' }"
                >Home</router-link
            >
            <a href="#"> </a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div
                class="offcanvas offcanvas-end bg-dark text-light"
                tabindex="-1"
                id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel"
            >
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
                        {{ user.nombre }}
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="offcanvas"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <router-link
                                class="nav-link"
                                :to="{ name: 'home.about' }"
                                >Profile</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link
                                class="nav-link"
                                :to="{ name: 'users' }"
                                >Users</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link
                                class="nav-link"
                                :to="{ name: 'scopes' }"
                                >Roles</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link
                                class="nav-link"
                                :to="{ name: 'channels' }"
                                >Broadcasts</router-link
                            >
                        </li>
                        <li class="nav-item dropdown">
                            <a
                                class="nav-link dropdown-toggle"
                                href="#"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                Servicios
                            </a>
                            <ul class="dropdown-menu bg-primary">
                                <li>
                                    <router-link
                                        class="dropdown-item"
                                        :to="{ name: 'clients' }"
                                        >Registrar</router-link
                                    >
                                </li>
                                <li>
                                    <router-link
                                        class="dropdown-item"
                                        :to="{ name: 'tokens' }"
                                        >Sessiones de
                                        microservicios</router-link
                                    >
                                </li>
                                <li>
                                    <router-link
                                        class="dropdown-item"
                                        :to="{ name: 'personalTokens' }"
                                        >API Tokens</router-link
                                    >
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <button
                                class="nav-link text-danger"
                                @click="logout"
                            >
                                Logout
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</template>
<script>
export default {
    props: ["user"],

    data() {
        return {
            data: {},
        };
    },

    methods: {
        logout() {
            this.$server
                .post("/logout")
                .then((res) => {
                    window.location.href = res.data.data;
                })
                .catch((e) => {
                    if (e.response) {
                        console.log(e.response);
                    }
                });
        },
    },
};
</script>
