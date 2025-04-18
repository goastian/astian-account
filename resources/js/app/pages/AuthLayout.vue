<template>
    <q-layout view="hHh Lpr lff" v-if="$user.id">
        <q-header class="bg-positive text-white">
            <q-toolbar>
                <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

                <q-toolbar-title>
                    <q-avatar>
                        <img src="../../../img/favicon.png" />
                    </q-avatar>
                    {{ $app_name }}
                </q-toolbar-title>
            </q-toolbar>
        </q-header>

        <q-drawer show-if-above v-model="leftDrawerOpen" side="left" elevated>
            <q-list padding class="menu-list">
                <q-expansion-item
                    v-for="(menu, index) in menus"
                    :key="index"
                    expand-separator
                    :label="menu.name"
                    :icon="menu.icon"
                    v-show="menu.show"
                    header-class="text-positive"
                >
                    <q-item
                        v-for="(item, i) in menu.menu"
                        :key="i"
                        clickable
                        v-ripple
                        @click="open(item)"
                        class="item-admin"
                        :active="$route.name == item.route"
                        active-class="active"
                    >
                        <q-item-section avatar>
                            <q-icon :name="item.icon" color="primary" />
                        </q-item-section>
                        <q-item-section class="text-primary">
                            {{ item.name }}
                        </q-item-section>
                    </q-item>
                </q-expansion-item>
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
            nav: [
                {
                    name: "Dashboard",
                    icon: "mdi-home",
                    route: "about",
                },
                {
                    name: "Personal Information",
                    icon: "mdi-account",
                    route: "information",
                },
                {
                    name: "Security",
                    icon: "mdi-shield-outline",
                    route: "security",
                },
                {
                    name: "Payment and Suscriptions",
                    icon: "mdi-account-credit-card",
                    route: "payment",
                },
                {
                    name: "Help",
                    icon: "mdi-help",
                    route: "help",
                },
            ],
            menus: [
                {
                    name: "Account",
                    icon: "mdi-account-star",
                    show: true,
                    menu: [
                        { name: "Me", route: "about", icon: "mdi-information" },
                    ],
                },
                {
                    name: "Admin",
                    icon: "mdi-shield-crown-outline",
                    show: this.hasGroup("administrator"),
                    menu: [
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
    },
};
</script>
