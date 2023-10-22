<template>
    <v-nav class="px-0 py-0">
        <v-item :path="{ name: 'clients' }">Clientes</v-item>
        <v-item :path="{ name: 'tokens' }">Tokens</v-item>
        <v-item :path="{ name: 'personalTokens' }">Personal Tokens</v-item>
        <v-item :path="{ name: 'users' }">Usuarios</v-item>
    </v-nav>
    <section class="container-fulid px-1 py-2">
        <router-view></router-view>
    </section>
</template>
<script>
import VNav from "./Dashboad/Navbar.vue";
import VItem from "./Dashboad/NavItem.vue";

export default {
    components: {
        VNav,
        VItem,
    },
};
</script>
