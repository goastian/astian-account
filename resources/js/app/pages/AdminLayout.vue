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
            <q-list bordered>
                <div v-for="(item, index) in menus" :key="index">
                    <q-separator />
                    <q-item
                        clickable
                        v-ripple
                        @click="open(item)"
                        :active="$route.name === item.route"
                        active-class="active"
                        :class="{
                            'bg-grey-2 text-primary':
                                $route.name === item.route,
                        }"
                    >
                        <q-item-section avatar>
                            <q-avatar
                                color="primary"
                                text-color="white"
                                :icon="item.icon"
                            />
                        </q-item-section>

                        <q-item-section class="text-primary">{{
                            item.name
                        }}</q-item-section>
                    </q-item>
                </div>
            </q-list>
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
                    name: "My Account",
                    route: "account.me",
                    icon: "mdi-account-cog-outline",
                },
                {
                    name: "Users",
                    route: "admin.users.index",
                    icon: "mdi-account-multiple",
                },
                {
                    name: "Clients",
                    route: "admin.clients.index",
                    icon: "mdi-apps",
                },
                {
                    name: "Groups",
                    route: "admin.groups.index",
                    icon: "mdi-account-group",
                },
                {
                    name: "Services",
                    route: "admin.services.index",
                    icon: "mdi-text-box-check",
                },
                {
                    name: "Roles",
                    route: "admin.roles.index",
                    icon: "mdi-format-list-group",
                },
                {
                    name: "Broadcasts",
                    route: "admin.broadcasts.index",
                    icon: "mdi-broadcast",
                },
                {
                    name: "Plans",
                    route: "admin.plans.index",
                    icon: "mdi-cash-clock",
                },
                {
                    name: "Transactions",
                    route: "admin.transactions.index",
                    icon: "mdi-account-cash-outline",
                },
                {
                    name: "Terminal",
                    route: "terminal.index",
                    icon: "mdi-console",
                },
                {
                    name: "Settings",
                    route: "/settings",
                    icon: "mdi-cogs",
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

        goToAccount() {
            this.$router.push({ name: "account.me" });
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
