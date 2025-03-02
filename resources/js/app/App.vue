<template>
    <v-app v-if="user.id">
        <v-app-bar color="blue-lighten-1" :elevation="0">
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-app-bar-title>{{ app_name }}</v-app-bar-title>
            <v-profile></v-profile>
        </v-app-bar>

        <v-navigation-drawer
            v-model="drawer"
            :permanent="$vuetify.display.lgAndUp"
            :temporary="!$vuetify.display.lgAndUp"
            class="custom-drawer"
        >
            <v-list nav>
                <v-expansion-panels>
                    <v-expansion-panel
                        v-for="(menu, index) in menus()"
                        :key="index"
                    >
                        <v-expansion-panel-title v-if="menu.show">
                            <v-icon left>{{ menu.icon }}</v-icon>
                            {{ menu.name }}
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            <v-list-item
                                v-for="(item, i) in menu.menu"
                                :key="i"
                                @click="open(item)"
                            >
                                <v-icon left>{{ item.icon }}</v-icon>
                                {{ item.name }}
                            </v-list-item>
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-list>
        </v-navigation-drawer>

        <v-main>
            <div class="mx-1 my-2">
                <router-view></router-view>
            </div>
        </v-main>
    </v-app>

    <div v-if="!user.id">
        <div
            id="loading-screen"
            class="fixed inset-0 flex items-center justify-center bg-white z-50"
        >
            <div class="text-center">
                <div
                    class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"
                ></div>
                <p class="text-lg font-medium text-gray-700 animate-pulse">
                    Loading ...
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { computed, nextTick } from "vue";
export default {
    data() {
        return {
            app_name: "",
            drawer: true,
            user: {},
            errors: {},
        };
    },

    provide() {
        return {
            $user: computed(() => this.user),
        };
    },

    created() {
        this.authenticated();
    },

    mounted() {
        this.appData();
    },

    methods: {
        async authenticated() {
            try {
                const res = await this.$server.get("/api/gateway/user");
                if (res.status === 200) {
                    this.user = res.data;
                }
            } catch (e) {}
        },

        async appData() {
            await nextTick();
            const app = document.querySelector("#app");
            this.app_name = app.dataset.appName;
        },

          hasGroup(name) {
            return this.user.groups.some(
                (item) => item.name == name || item.name == "admin"
            );
        },

        menus() {
            return [
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
                    show: this.hasGroup("admin"),
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
            ];
        },
        open(item) {
            this.$router.push({ name: item.route });
        },
    },
};
</script>

<style scoped lang="css">
.custom-drawer {
    width: 250px;
}
</style>
