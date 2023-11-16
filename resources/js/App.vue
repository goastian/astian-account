<template>
    <v-nav class="px-0 py-0" :user="user">
        <v-item :path="{ name: 'clients' }">Clientes</v-item>
        <v-item :path="{ name: 'tokens' }">Clientes Tokens</v-item>
        <v-item :path="{ name: 'personalTokens' }">Personal Tokens</v-item>
        <v-item :path="{ name: 'users' }">Usuarios</v-item>
        <v-item :path="{ name: 'scopes' }">Scopes</v-item>
        <v-item :path="{ name: 'channels' }">Broadcasts</v-item>
    </v-nav>
    <section >
        <router-view />
    </section>
    <footer>
        
    </footer>
</template>
<script>
export default {
    data() {
        return {
            user: {},
        };
    },

    created() {
        this.authenticated();
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
    },
};
</script>
