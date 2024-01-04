<template>
    <v-nav :user="user"></v-nav>
    <section class="container-fluid px-0 py-0">
        <router-view />
    </section>
    <footer class="fixed-bottom">
    </footer>
</template>
<script>
import VNav from "./Pages/Dashboad/Navbar.vue";

export default {
    components: {
        VNav,
    },

    data() {
        return {
            user: {},
        };
    },

    created() {
        this.authenticated();
        this.authenticated()
    },

    methods: {
        authenticated() {
            window.axios
                .get("/api/gateway/user")
                .then((res) => {
                    this.user = res.data;
                    window.$auth = res.data;
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
