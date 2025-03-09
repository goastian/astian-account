<template>
    <q-layout view="hHh lpR lFf" v-if="$user.id">
        <q-header elevated class="bg-primary text-white">
            <q-toolbar>
                <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

                <q-toolbar-title> {{ $app_name }} </q-toolbar-title>
                <q-space />
                <v-profile></v-profile>
            </q-toolbar>
        </q-header>

        <q-drawer
            show-if-above
            v-model="leftDrawerOpen"
            side="left"
            behavior="desktop"
            bordered
        >
            <q-list bordered>
                <q-expansion-item
                    v-for="(menu, index) in menus"
                    :key="index"
                    expand-separator
                    :label="menu.name"
                    :icon="menu.icon"
                    v-show="menu.show"
                >
                    <q-item
                        v-for="(item, i) in menu.menu"
                        :key="i"
                        clickable
                        v-ripple
                        @click="open(item)"
                    >
                        <q-item-section avatar>
                            <q-icon color="blue" :name="item.icon" />
                        </q-item-section>
                        <q-item-section>
                            {{ item.name }}
                        </q-item-section>
                    </q-item>
                </q-expansion-item>
            </q-list>
        </q-drawer>

        <q-page-container>
            <div class="px-2 py-2">
                <router-view />
            </div>
        </q-page-container>
    </q-layout>

    <q-page v-if="!$user.id" class="fixed-full flex flex-center bg-white">
        <div class="text-center">
            <q-spinner size="3rem" color="indigo" class="q-mb-md" />
            <p class="text-h6 text-grey-7 q-animate--pulse">Loading ...</p>
        </div>
    </q-page>
</template>

<script>
export default {
    inject: ["$user", "$app_name"],

    data() {
        return {
            drawer: true,
            errors: {},
            leftDrawerOpen: false,
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
                            icon: "mdi-account-group",
                        },
                        {
                            name: "Clients",
                            route: "admin.clients.index",
                            icon: "mdi-apps",
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

<style scoped lang="css">
.custom-drawer {
    width: 250px;
}
</style>
