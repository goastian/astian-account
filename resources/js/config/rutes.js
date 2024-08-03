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
    { path: "/", name: "home", component: Profile, meta: { auth: true } },

    {
        path: "/notifications",
        name: "notify",
        component: Push,
        meta: { auth: true },
    },
    {
        path: "/notifications/Read",
        name: "notify.read",
        component: Read,
        meta: { auth: true },
    },
    {
        path: "/notifications/unread",
        name: "notify.unread",
        component: Unread,
        meta: { auth: true },
    },

    {
        path: "/security",
        name: "security",
        component: Security,
        meta: { auth: true },
    },

    {
        path: "/private-clients",
        name: "clients",
        component: Clients,
        meta: { auth: true },
    },
    {
        path: "/tokens",
        name: "tokens",
        component: Tokens,
        meta: { auth: true },
    },
    {
        path: "/personal-tokens",
        name: "personalTokens",
        component: PersonalTokens,
        meta: { auth: true },
    },
    { path: "/users", name: "users", component: Users, meta: { auth: true } },
    { path: "/scopes", name: "scopes", component: Roles, meta: { auth: true } },
    {
        path: "/channels",
        name: "channels",
        component: Channel,
        meta: { auth: true },
    },
];

export const router = createRouter({
    history: createWebHistory(),
    routes,
});
