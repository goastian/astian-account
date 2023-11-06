import { createRouter, createWebHistory } from "vue-router";

import Clients from "../Pages/OAuth/Clients/Index.vue";
import Tokens from "../Pages/OAuth/Tokens/Index.vue";
import PersonalTokens from "../Pages/OAuth/Personal/Index.vue";
import Users from "../Pages/Users/Index.vue";
import Roles from "../Pages/Role/Index.vue";
import Info from "../Pages/Config/Index.vue";

const routes = [
    { path: "/", name: "clients", component: Clients },
    { path: "/tokens", name: "tokens", component: Tokens },
    {
        path: "/personal-tokens",
        name: "personalTokens",
        component: PersonalTokens,
    },
    { path: "/users", name: "users", component: Users },
    { path: "/scopes", name: "scopes", component: Roles },
    { path: "/info", name: "info", component: Info },
];

export const router = createRouter({
    history: createWebHistory(),
    routes,
});
