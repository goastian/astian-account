<template>
    <q-layout view="hHh Lpr lff" v-if="$user.id">
        <q-header class="bg-positive text-white">
            <q-toolbar>
                <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

                <q-toolbar-title>
                    <q-avatar>
                        <img src="../../../img/favicon.svg" />
                    </q-avatar>
                    {{ $app_name }}
                </q-toolbar-title>

                <v-profile></v-profile>
            </q-toolbar>
        </q-header>

        <q-drawer show-if-above v-model="leftDrawerOpen" side="left">
            <q-expansion-item
                v-for="(menu, index) in menus"
                :key="index"
                expand-separator
                :label="menu.name"
                :icon="menu.icon"
                v-show="menu.show"
                header-class="text-positive"
                default-opened
            >
                <q-item
                    v-for="(item, i) in menu.menu"
                    :key="i"
                    clickable
                    v-ripple
                    @click="open(item)"
                    class="item-admin"
                    :active="$route.name === item.route"
                    active-class="active"
                    :class="{
                        'bg-grey-2 text-primary': $route.name === item.route,
                    }"
                >
                    <q-item-section avatar>
                        <q-icon :name="item.icon" color="primary" />
                    </q-item-section>
                    <q-item-section class="text-primary">
                        {{ item.name }}
                    </q-item-section>
                </q-item>
            </q-expansion-item>

            <q-separator inset="item" />

            <q-item
                v-if="hasGroup('administrator')"
                clickable
                v-ripple
                :active="$route.name === 'admin.users.index'"
                @click="goToAdmin"
            >
                <q-item-section avatar>
                    <q-icon color="positive" name="mdi-security" />
                </q-item-section>

                <q-item-section class="text-primary">Admin</q-item-section>
            </q-item>
        </q-drawer>

        <q-page-container>
            <q-page :class="{ 'no-radius': !leftDrawerOpen }">
                <router-view />
            </q-page>
        </q-page-container>

        <q-page v-if="!$user.id" class="fixed-full flex flex-center bg-white">
            <div class="text-center">
                <q-spinner size="3rem" color="indigo" class="q-mb-md" />
                <p class="text-h6 text-grey-7 q-animate--pulse">Loading ...</p>
            </div>
        </q-page>
    </q-layout>
</template>

<script>
export default {
    inject: ["$user", "$app_name"],

    data() {
        return {
            drawer: true,
            errors: {},
            leftDrawerOpen: true,
            menus: [
                {
                    name: "Account",
                    icon: "mdi-account-star",
                    show: true,
                    menu: [
                        {
                            name: "Me",
                            route: "account.me",
                            icon: "mdi-information",
                        },
                        {
                            name: "Information",
                            route: "account.information",
                            icon: "mdi-account-details-outline",
                        },
                        {
                            name: "Password",
                            route: "account.password",
                            icon: "mdi-lock-reset",
                        },
                        {
                            name: "2FA",
                            route: "account.2fa",
                            icon: "mdi-two-factor-authentication",
                        },
                        {
                            name: "Subscriptions",
                            route: "account.subscriptions",
                            icon: "mdi-gift-outline"
                        },
                        {
                            name: "Store",
                            route: "plans.index",
                            icon: "mdi-store-search"
                        }
                    ],
                },

                {
                    name: "Tools",
                    icon: "mdi-tools",
                    show: true,
                    menu: [
                        {
                            name: "Clients",
                            route: "developer.clients.index",
                            icon: "mdi-wan",
                        },
                        {
                            name: "API Key",
                            route: "developer.api.index",
                            icon: "mdi-shield-key-outline",
                        },
                    ],
                },
            ],
        };
    },

    methods: {
        hasGroup(name) {
            return this.$user.groups.some(
                (item) => item.slug == name || item.slug == "administrator"
            );
        },

        toggleLeftDrawer() {
            this.leftDrawerOpen = !this.leftDrawerOpen;
        },

        open(item) {
            try {
                this.$router.push({ name: item.route });
            } catch (error) {
                window.location.href = item.route;
            }
        },

        goToAdmin() {
            this.$router.push({ name: "admin.users.index" });
        },
    },
};
</script>
<style scoped>
.item-admin.active {
    background-color: rgba(0, 150, 136, 0.1);
    border-left: 3px solid #26a69a;
}
</style>
