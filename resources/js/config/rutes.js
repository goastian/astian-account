import { createRouter, createWebHistory } from "vue-router";

import Clients from "../Pages/OAuth/Clients/Index.vue";
import Tokens from "../Pages/OAuth/Tokens/Index.vue";
import PersonalTokens from "../Pages/OAuth/Personal/Index.vue";
import Users from "../Pages/Users/Index.vue";
import Roles from "../Pages/Role/Index.vue"; 
import Channel from "../Pages/Broadcast/Index.vue";
import Home from "../Pages/Account/Index.vue";
import About from "../Pages/Account/About.vue";
import Credential from "../Pages/Account/Credential.vue";
import M2FA from "../Pages/Account/2FA.vue";
import TOTP from "../Pages/Account/TOTP.vue";

const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
        children: [
            { path: "", name: "home.about", component: About },
            { path: "/revoke", name: "home.revoke", component: Credential },
            { path: "/2FA", name: "home.2fa", component: M2FA },
            { path: "/TOTP", name: "home.totp", component: TOTP }
        ],
    },

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
