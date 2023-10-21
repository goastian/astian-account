import { createRouter, createWebHistory } from "vue-router";

import Clients from "../OAuth/Clients/Index.vue";
import Tokens from "../OAuth/Tokens/Index.vue";
import PersonalTokens from "../OAuth/Personal/Index.vue";

const routes = [
    { path: "/", name: "clients", component: Clients },
    { path: "/tokens", name: "tokens", component: Tokens },
    {
        path: "/personal-tokens",
        name: "personalTokens",
        component: PersonalTokens,
    },
];

export const router = createRouter({
    history: createWebHistory(),
    routes,
})
 