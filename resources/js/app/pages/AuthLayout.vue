<template>
    <q-layout view="hHh Lpr lff" v-if="$user.id">
        <q-drawer
            v-model="state.isVisible"
            show-if-above
            :width="270"
            :breakpoint="500"
            class="nav"
        >
            <q-scroll-area v-if="$user.groups[0].name === 'member' " class="nav-user-scroll">
                <q-list
                    padding
                    class=""
                >
                    <q-item
                        v-for="(nav, index) in navs"
                        :key="index"
                        :href="nav.route"
                        class="item-user"
                        :active="$route.name == nav.route"
                        active-class="active-link"
                    >
                        <q-item-section avatar>
                            <q-icon :name="nav.icon" />
                        </q-item-section>
                        <q-item-section>
                            {{ nav.name }}
                        </q-item-section>
                    </q-item>
                </q-list>
            </q-scroll-area>

            <q-scroll-area v-else class="nav-scroll">
                <q-list padding class="menu-list">
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
                            class="item-admin"
                            :active="$route.name == item.route"
                            active-class="active"
                        >
                            <q-item-section avatar>
                                <q-icon :name="item.icon" />
                            </q-item-section>
                            <q-item-section>
                                {{ item.name }}
                            </q-item-section>
                        </q-item>
                    </q-expansion-item>
                </q-list>
            </q-scroll-area>

            <div class="absolute-top container-logo">
                <img src="/img/Astian-1.png" class="logo" />
            </div>
        </q-drawer>

        <q-page-container>
            <q-page :class="{ 'full-page': !state.isVisible }">
                <div class="main-container">
                    <router-view />
                </div>
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
import { reactive } from 'vue';
export default {
    inject: ["$user", "$app_name"],

    data() {
        return {
            drawer: true,
            errors: {},
            leftDrawerOpen: true,
            state: reactive({
                isVisible: true,
            }),
            navs: [
                {
                    name: "Dashboard",
                    icon: "mdi-home",
                    route: "about",
                },
                /*
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
                */
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
                            name: "Scopes",
                            route: "admin.scopes.index",
                            icon: "mdi-circle-outline",
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

    provide() {
        return {
            state: this.state
        }
    },

    methods: {
        hasGroup(name) {
            return this.$user.groups.some(
                (item) => item.slug == name || item.slug == "administrator"
            );
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

.header {
    background-color: var(--bg);
    color: var(--text);
}

.header-title {
    width: 200px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.header-title .q-img {
    width: 30px;
    height: 30px;
}

.header-title h1 {
    font-size: 1rem;
}

.nav-scroll {
    height: calc(100% - 70px);
    padding: 1rem 0;
    margin-top: 70px;
}

.nav-list {
    padding: 0 1rem;
}

.item-admin {
    color: var(--text-link);
}

.item-admin:hover {
    background-color: var(--bg-link-hover);
    color: var(--text-link-hover);
}

.active {
    background-color: var(--bg-link-active);
    color: var(--text-link-active);
}

.nav-user-scroll {
    height: calc(100% - 70px);
    margin-top: 70px;
    padding: 1rem .5rem;
}

.item-user {
    border-radius: .6rem;
    color: var(--text-link);
    padding: 0 1rem;
}

.active-link {
    background-color: var(--bg-link-active);
    color: var(--text-link-active);
}

.item-user:hover {
    background-color: var(--bg-link-hover);
    color: var(--text-link-hover);
}

.q-page {
    padding: .5rem .5rem .5rem 0;
    height: 100vh;
    min-height: 100vh;
    width: calc(100vw - 270px);
    background-color: white;
}

.full-page {
    padding: .5rem;
    width: 100vw;
    transition: .2s;
}

.main-container {
    border-radius: .7rem;
    border: .06rem solid var(--border-color);
    min-height: 100%;
    background-color: var(--bg-primary);
}

.container-logo {
    height: 70px;
    padding: .5rem;
    display: flex;
    justify-content: center;
    align-items: center;
    border-bottom: .06rem solid var(--border-color);
    gap: 1rem;
}

.logo {
    width: 70%;
    height: 30px;
}

.mini {
  width: 70px; /* Ajusta este valor según lo que quieras */
}
</style>
