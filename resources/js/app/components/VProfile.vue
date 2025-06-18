<template>
    <div class="text-center">
        <q-btn flat round dense icon="mdi-dots-vertical-circle-outline">
            <q-menu fit anchor="bottom right" self="top right">
                <q-card style="min-width: 240px" class="q-pa-sm">
                    <!-- User Info -->
                    <div v-if="user?.id" class="q-pa-sm flex items-center">
                        <q-avatar size="40px" class="q-mr-sm">
                            <q-icon color="primary" name="mdi-account-circle" size="28px" />
                        </q-avatar>
                        <div>
                            <div class="text-weight-medium">
                                {{ user.name }} {{ user.last_name }}
                            </div>
                            <div class="text-caption text-grey">
                                {{ user.email }}
                            </div>
                        </div>
                    </div>

                    <q-separator class="q-my-sm" />

                    <!-- Menu Options -->
                    <q-list padding>
                        <q-item clickable @click="homePage">
                            <q-item-section avatar>
                                <q-icon color="primary" name="mdi-home" />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>Home page</q-item-label>
                            </q-item-section>
                        </q-item>

                        <q-separator class="q-my-sm" />

                        <q-item v-if="user?.id" clickable @click="myAccount">
                            <q-item-section avatar>
                                <q-icon
                                    color="primary"
                                    name="mdi-home-account"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>My account</q-item-label>
                            </q-item-section>
                        </q-item>

                        <q-separator class="q-my-sm" v-if="user?.id" />

                        <q-item
                            v-if="user?.id"
                            clickable
                            v-close-popup
                            @click="logout"
                        >
                            <q-item-section avatar>
                                <q-icon color="negative" name="mdi-logout" />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label class="text-negative">
                                    Logout
                                </q-item-label>
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
                const res = await this.$server.post(
                    this.$page.props.auth_routes["logout"]
                );
                if (res.status === 200) {
                    window.location.href =
                        this.$page.props.auth_routes["login"];
                }
            } catch (error) {
                console.error("Error logging out:", error);
            }
        },

        homePage() {
            window.location.href = "/";
        },

        myAccount() {
            window.location.href = this.$page.props.user_dashboard;
        },
    },
};
</script>
