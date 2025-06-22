<template>
    <q-layout view="hHh lpR fFf">
        <!-- Header -->
        <q-header elevated>
            <q-toolbar>
                <q-toolbar-title>
                    <q-btn
                        dense
                        flat
                        round
                        icon="mdi-view-dashboard-variant"
                        @click="homePage"
                    />
                    {{ $page.props.app_name }}
                </q-toolbar-title>
                <v-theme />
                <q-btn
                    flat
                    label="Plans"
                    @click="open($page.props.guest_routes['plans'])"
                />

                <q-btn
                    v-if="!$page.props.user?.id"
                    flat
                    label="Register"
                    @click="open($page.props.auth_routes['register'])"
                />
                <q-btn
                    v-if="!$page.props.user?.id"
                    flat
                    label="Login"
                    @click="guest = true"
                />
                <v-profile />
            </q-toolbar>
        </q-header>

        <!-- Main Content -->
        <q-page-container>
            <slot name="body" />
        </q-page-container>

        <!-- Footer 
        <q-footer class="text-center" elevated>
            <q-toolbar>
                <q-toolbar-title class="text-center">
                    © 2025 Mi Aplicación
                </q-toolbar-title>
            </q-toolbar>
        </q-footer>-->
        <v-login :guest="guest" @close="guest = false" />
    </q-layout>
</template>

<script>
export default {
    data() {
        return {
            guest: false,
        };
    },
    methods: {
        open(url) {
            window.location.href = url;
        },

        isActive(item) {
            return item.route == window.location.href;
        },
        homePage() {
            window.location.href = this.$page.props.user_routes[0].menu[0]['route'];
        },
    },
};
</script>
