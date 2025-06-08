<template>
    <q-layout view="hHh lpR fFf">
        <q-header elevated class="bg-primary text-white">
            <q-toolbar>
                <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

                <q-toolbar-title>
                    <q-avatar icon="mdi-currency-usd"> </q-avatar>
                    Partner system
                </q-toolbar-title>
                <v-theme />
                <v-profile></v-profile>
            </q-toolbar>
        </q-header>

        <q-drawer show-if-above v-model="leftDrawerOpen" side="left" bordered>
            <q-list bordered>
                <div v-for="(item, index) in menus" :key="index">
                    <q-separator />
                    <q-item
                        clickable
                        v-ripple
                        @click="open(item)"
                        :active="isActive(item)"
                        active-class="secondary"
                    >
                        <q-item-section avatar>
                            <q-avatar class="text-primary" :icon="item.icon" />
                        </q-item-section>

                        <q-item-section>{{ item.name }}</q-item-section>
                    </q-item>
                </div>
            </q-list>
        </q-drawer>

        <q-page-container>
            <slot />
        </q-page-container>
    </q-layout>
</template>

<script>
export default {
    data() {
        return {
            leftDrawerOpen: true,
            app_name: "",
        };
    },

    computed: {
        menus() {
            return this.$page.props.partner_routes;
        },
    },

    created() {
        this.app_name = this.$page.props.app_name;
    },

    methods: {
        toggleLeftDrawer() {
            this.leftDrawerOpen = !this.leftDrawerOpen;
        },

        open(item) {
            window.location.href = item.route;
        },

        isActive(item) {
            return item.route == window.location.href;
        },
    },
};
</script>
