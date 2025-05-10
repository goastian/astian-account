<template>
    <q-layout view="hHh Lpr lff" v-if="user.id">
        <q-header class="bg-positive text-white">
            <q-toolbar>
                <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />
                <q-toolbar-title>
                    <q-avatar>
                        <img src="../../../img/favicon.png" />
                    </q-avatar>
                    {{ app_name }}
                </q-toolbar-title>
                <v-profile />
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
                    :active="$page.component === item.route"
                    active-class="active"
                    :class="{
                        'bg-grey-2 text-primary':
                            $page.component === item.route,
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
                @click="goToAdmin"
            >
                <q-item-section avatar>
                    <q-icon color="positive" :name="admin_dashboard.icon" />
                </q-item-section>
                <q-item-section class="text-primary">
                    {{ admin_dashboard.name }}
                </q-item-section>
            </q-item>
        </q-drawer>

        <q-page-container>
            <q-page :class="{ 'no-radius': !leftDrawerOpen }">
                <slot />
            </q-page>
        </q-page-container>
    </q-layout>

    <q-page v-else class="fixed-full flex flex-center bg-white">
        <div class="text-center">
            <q-spinner size="3rem" color="indigo" class="q-mb-md" />
            <p class="text-h6 text-grey-7 q-animate--pulse">Loading ...</p>
        </div>
    </q-page>
</template>

<script>
export default {
    data() {
        return {
            leftDrawerOpen: true,
            user: {},
            app_name: "",
            menus: [],
            admin_dashboard: [],
        };
    },

    created() {
        this.user = this.$page.props.user;
        this.app_name = this.$page.props.app_name;
        this.menus = this.$page.props.user_routes;
        this.admin_dashboard = this.$page.props.admin_dashboard;
    },

    methods: {
        hasGroup(name) {
            return this.user.groups.some(
                (item) => item.slug === name || item.slug === "administrator"
            );
        },
        toggleLeftDrawer() {
            this.leftDrawerOpen = !this.leftDrawerOpen;
        },
        open(item) {
            window.location.href = item.route;
        },
        goToAdmin() {
            window.location.href = this.admin_dashboard.route;
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
