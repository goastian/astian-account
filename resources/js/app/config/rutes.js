import { createRouter, createWebHistory } from "vue-router";

import Clients from "../pages/OAuth/Clients/Index.vue";
import Tokens from "../pages/OAuth/Tokens/Index.vue";
import PersonalTokens from "../pages/OAuth/Personal/Index.vue";
import Users from "../pages/Users/Index.vue";
import Roles from "../pages/Role/Index.vue";
import Channel from "../pages/Broadcast/Index.vue";
import Profile from "../pages/Account/Profile.vue";
import Security from "../pages/Account/Security.vue";
import Push from "../pages/Notify/Push.vue";
import Read from "../pages/Notify/Read.vue";
import Unread from "../pages/Notify/Unread.vue";

const routes = [
    { path: "/", name: "home", component: Profile },

    {
        path: "/notifications",
        name: "notify",
        component: Push,
    },
    {
        path: "/notifications/Read",
        name: "notify.read",
        component: Read,
    },
    {
        path: "/notifications/unread",
        name: "notify.unread",
        component: Unread,
    },

    {
        path: "/security",
        name: "security",
        component: Security,
    },

    {
        path: "/private-clients",
        name: "clients",
        component: Clients,
    },
    {
        path: "/tokens",
        name: "tokens",
        component: Tokens,
    },
    {
        path: "/personal-tokens",
        name: "personalTokens",
        component: PersonalTokens,
    },
    { path: "/users", name: "users", component: Users },
    { path: "/scopes", name: "scopes", component: Roles },
    {
        path: "/channels",
        name: "channels",
        component: Channel,
    },
];

export const router = createRouter({
    history: createWebHistory(),
    routes,
});
