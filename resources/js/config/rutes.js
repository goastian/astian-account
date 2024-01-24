import { createRouter, createWebHistory } from "vue-router";

import Clients from "../Pages/OAuth/Clients/Index.vue";
import Tokens from "../Pages/OAuth/Tokens/Index.vue";
import PersonalTokens from "../Pages/OAuth/Personal/Index.vue";
import Users from "../Pages/Users/Index.vue";
import Roles from "../Pages/Role/Index.vue";
import Channel from "../Pages/Broadcast/Index.vue";
import Profile from "../Pages/Account/Profile.vue";
import Security from "../Pages/Account/Security.vue";
import Devices from "../Pages/Account/Devices.vue";
import Push from "../Pages/Notify/Push.vue";
import Read from "../Pages/Notify/Read.vue";
import Unread from "../Pages/Notify/Unread.vue";

const routes = [
    {
        path: "/",
        name: "profile",
        component: Profile,
    },

    { path: "/notifications", name: "notify", component: Push },
    { path: "/notifications/Read", name: "notify.read", component: Read },
    { path: "/notifications/unread", name: "notify.unread", component: Unread },

    { path: "/security", name: "security", component: Security },
    { path: "/devices", name: "devices", component: Devices },

    { path: "/clients", name: "clients", component: Clients },
    { path: "/tokens", name: "tokens", component: Tokens },
    {
        path: "/personal-tokens",
        name: "personalTokens",
        component: PersonalTokens,
    },
    { path: "/users", name: "users", component: Users },
    { path: "/scopes", name: "scopes", component: Roles },
    { path: "/channels", name: "channels", component: Channel },
];

export const router = createRouter({
    history: createWebHistory(),
    routes,
});
