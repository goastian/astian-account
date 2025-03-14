<template>
    <q-layout view="hHh Lpr lff" v-if="$user.id">
        <q-header elevated class="">
            <q-toolbar>
                <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />
                <q-toolbar-title> {{ $app_name }} </q-toolbar-title>
                <q-space />
                <v-profile />
            </q-toolbar>
        </q-header>

        <q-drawer
            v-mode="leftDrawerOpen"
            show-if-above
            :width="350"
            :breakpoint="400"
        >
            <q-scroll-area class="nav-sroll">
                <q-list class="nav-list column q-gutter-y-sm">
                    <q-expansion-item
                        v-if="$user.groups[0].slug == 'administrator'"
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
                    <q-item
                        v-else
                        v-for="(item, index) in nav"
                        :key="index"
                        clickable
                        @click="open(item)"
                        class="item-user"
                        :active="$route.name == item.route"
                        active-class="active-link"
                    >
                        <q-item-section avatar>
                            <q-icon :name="item.icon" />
                        </q-item-section>
                        <q-item-section>
                            {{ item.name }}
                        </q-item-section>
                    </q-item>
                </q-list>
            </q-scroll-area>
        </q-drawer>

        <q-page-container>
            <router-view />
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
            leftDrawerOpen: false,
            nav: [
                {
                    name: "Dashboard",
                    icon: "mdi-home",
                    route: "about",
                },
                {
                    name: "Personal Information",
                    icon: "mdi-account",
                    route: "information"
                },
                {
                    name: "Security",
                    icon: "mdi-shield-outline",
                    route: "security"
                },
                {
                    name: "Payment and Suscriptions",
                    icon: "mdi-account-credit-card",
                    route: "payment"
                },
                {
                    name: "Help",
                    icon: "mdi-help",
                    route: "help"
                }
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
                            icon: "mdi-text-box-check"
                        },
                        {
                            name: "Roles",
                            route: "admin.roles.index",
                            icon: "mdi-format-list-group"
                        },
                        {
                            name: "Scopes",
                            route: "admin.scopes.index",
                            icon: "mdi-circle-outline"
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

.nav-top {
    height: 60px;
    padding: 0 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.nav-top .q-img {
    width: 30px;
    height: 30px;
}

.nav-top h1 {
    font-size: 1rem;
}

.nav-sroll {
    height: 100%;
    padding: 1rem 0;
}

.nav-list {
    padding: 0 1rem;
}

.item-user {
    border-radius: 2rem;
    padding: 0 2.5rem;
    color: var(--text-link);
}

.active-link {
    background-color: var(--bg-link-active);
    color: var(--text-link-active);
}

.item-user:hover {
    background-color: var(--bg-link-hover);
    color: var(--text-link-hover);
}
</style>
