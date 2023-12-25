<template>
    <nav class="navbar navbar-dark navbar-expand-sm px-0">
        <div class="container-fluid">
            <router-link to="#" class="navbar-brand text-md fw-bold text-xl">
            </router-link>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarScroll"
                aria-controls="navbarScroll"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul
                    class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll"
                    style="--bs-scroll-height: 100px"
                >
                    <slot></slot>
                </ul>
                <ul class="navbar-nav text-md navbar-nav-scroll">
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            {{ user.nombre }}
                        </a>
                        <ul
                            class="dropdown-menu dropdown-menu-lg-end dropdown-menu-dark"
                        >
                            <li>
                                <router-link
                                    class="px-2 text-light"
                                    style="text-decoration: none"
                                    :to="{ name: 'info' }"
                                    >Security</router-link
                                >
                            </li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li>
                                <button
                                    type="submit"
                                    class="dropdown-item text-md"
                                    @click="logout"
                                >
                                    Cerrar
                                </button>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
export default {
    props: {
        user: { type: Object, required: true },
    },

    methods: {
        logout() {
            window.axios
                .post("/logout")
                .then((res) => {
                    window.location.href = res.data.data;
                })
                .catch((e) => {
                    console.log(e);
                });
        },
    },
};
</script>
