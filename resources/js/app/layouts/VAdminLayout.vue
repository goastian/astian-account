<template>
    <q-layout view="lHh LpR lFf" v-if="user.id">
        <q-header class="header" :class="{'hid' : !leftDrawerOpen}">
            <q-toolbar class="row justify-between">
                <div class="row ga-1">
                    <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />
                    <div class="row items-center q-gutter-x-md header-title" v-if="!leftDrawerOpen">
                        <img src="../../../img/favicon.svg" width="30px"/>
                        <span>{{ app_name }}</span>
                    </div>
                </div>
                <div class="row">
                    <v-theme />
                    <v-profile />
                </div>
            </q-toolbar>
        </q-header>
        <!--
        <q-header>
            <q-toolbar>
                <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

                <q-toolbar-title>
                    <q-avatar>
                        <img src="../../../img/favicon.svg" />
                    </q-avatar>
                    {{ app_name }}
                </q-toolbar-title>

                <v-theme />
                <v-profile></v-profile>
            </q-toolbar>
        </q-header>
        -->

        <q-drawer show-if-above v-model="leftDrawerOpen" side="left" :width="260">
            <q-scroll-area class="scroll-area">
                <q-list
                    v-for="(item, index) in menus"
                    key="index"
                    v-show="item.show"
                >
                    <q-item
                        clickable
                        v-ripple
                        @click="open(item)"
                        :active="isActive(item)"
                        active-class="secondary"
                    >
                        <q-item-section avatar>
                            <q-avatar :icon="item.icon" />
                        </q-item-section>

                        <q-item-section>{{ item.name }}</q-item-section>
                    </q-item>
                </q-list>
            </q-scroll-area>

            <div class="absolute-top flex items-center justify-center px-4 py-4 title" style="height: 80px">
                <img src="../../../img/favicon.svg" width="30px"/>
                <span>{{ app_name }}</span>
            </div>
        </q-drawer>
        <!--
        <q-drawer show-if-above v-model="leftDrawerOpen" side="left">
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
        -->
        <q-page-container>
            <q-page :class="{ 'no-radius': !leftDrawerOpen }">
                <div :class="{ 'hid': !leftDrawerOpen }" class="main">
                    <div class="main-container">
                        <slot />
                    </div>
                </div>
            </q-page>
        </q-page-container>

        <q-page v-if="!user.id" class="fixed-full flex flex-center bg-white">
            <div class="text-center">
                <q-spinner size="3rem" color="indigo" class="q-mb-md" />
                <p class="text-h6 text-grey-7 q-animate--pulse">Loading ...</p>
            </div>
        </q-page>
    </q-layout>
</template>

<script>
export default {
    data() {
        return {
            drawer: true,
            errors: {},
            leftDrawerOpen: true,
            menus: [],
            user: [],
            app_name: "",
        };
    },

    created() {
        this.user = this.$page.props.user;
        this.app_name = this.$page.props.app_name;
        this.menus = this.$page.props.admin_routes;
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
<style scoped>
.item-admin.active {
    background-color: rgba(0, 150, 136, 0.1);
    border-left: 3px solid #26a69a;
}

.scroll-area {
    color: var(--q-color);
}

.item-admin.active {
    background-color: rgba(0, 150, 136, 0.1);
    border-left: 3px solid #26a69a;
}

.header {
    background-color: var(--q-background-primary);
    color: black;
    padding-top: .6rem;
    padding-right: .6rem;
}

.hid{
    padding-left: .6rem;
}

.header > div {
    border-radius: 1rem 1rem 0 0;
    border: solid .02rem var(--q-border);
    padding: .6rem .8rem;
    background-color: var(--q-background-secondary);
    color: var(--q-color);
}

.ga-1 {
    gap: 1rem;
}

.title {
    border-bottom: .02rem solid var(--q-border);
    gap: 1rem;
}

.scroll-area {
    height: calc(100% - 80px);
    margin-top: 80px;
}

.main {
    padding-right: .6rem;
    padding-bottom: .6rem;
    background-color: var(--q-background-primary);
}

.main-container {
    border-right: .02rem solid var(--q-border);
    border-left: .02rem solid var(--q-border);
    border-bottom: .02rem solid var(--q-border);
    border-radius: 0 0 1rem 1rem;
    height: 100%;
    background-color: var(--q-background-secondary);
}
</style>
