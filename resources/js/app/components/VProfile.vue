<template>
    <div class="text-center">
        <q-btn flat round icon="mdi-dots-vertical">
            <q-menu fit anchor="bottom right" self="top right">
                <q-card style="min-width: 200px">
                    <q-list>
                        <q-item v-if="user?.id">
                            <q-item-section>
                                <q-item-label
                                    >{{ user.name }}
                                    {{ user.last_name }}</q-item-label
                                >
                                <q-item-label caption>{{
                                    user.email
                                }}</q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-list>

                    <q-separator />

                    <q-list>
                        <q-item
                            v-if="!user?.id"
                            icon="mdi-home"
                            clickable
                            @click="homePage"
                        >
                            <q-item-section> Home page </q-item-section>
                        </q-item>
                        <q-item
                            v-if="user?.id"
                            icon="mdi-home-account"
                            clickable
                            @click="goToDashboard"
                        >
                            <q-item-section> My account </q-item-section>
                        </q-item>
                        <q-separator />
                        <q-item
                            v-if="user?.id"
                            clickable
                            v-close-popup
                            @click="logout"
                        >
                            <q-item-section>
                                <q-item-label>Logout</q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-card>
            </q-menu>
        </q-btn>
    </div>
</template>

<script>
export default {
    computed: {
        user() {
            return this.$page.props.user;
        },
    },

    methods: {
        async logout() {
            try {
                const res = await this.$server.post("/logout");
                if (res.status === 200) {
                    window.location.href = this.$page.props.login;
                }
            } catch (error) {
                console.error("Error logging out:", error);
            }
        },

        homePage() {
            window.location.href = "/";
        },

        goToDashboard() {
            window.location.href = this.$page.props.user_dashboard;
        },
    },
};
</script>
