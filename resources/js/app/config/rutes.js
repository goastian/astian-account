import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/about",
        name: "about",
        component: () => import("../pages/Account/About.vue"),
    },
    {
        path: "/admin/users",
        name: "users.index",
        component: () => import("../pages/Admin/Users/Index.vue"),
    },
    {
        path: "/admin/broadcasts",
        name: "broadcasts.index",
        component: () => import("../pages/Admin/Broadcast/Index.vue"),
    },
    {
        path: "/admin/roles",
        name: "roles.index",
        component: () => import("../pages/Admin/Role/Index.vue"),
    },
    {
        path: "/admin/services",
        name: "services.index",
        component: () => import("../pages/Admin/Service/Index.vue"),
    },
    {
        path: "/admin/scopes",
        name: "scopes.index",
        component: () => import("../pages/Admin/Scope/Index.vue"),
    },
    {
        path: "/developers/clients",
        name: "clients.index",
        component: () => import("../pages/OAuth/Clients/Index.vue"),
    },
    {
        path: "/developers/api_key",
        name: "api.index",
        component: () => import("../pages/OAuth/Personal/Index.vue"),
    },
];

export const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});
