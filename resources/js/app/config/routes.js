import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/account",
        component: () => import("../pages/UserLayout.vue"),
        children: [
            {
                path: "",
                name: "account.me",
                component: () => import("../pages/Account/About.vue"),
            },

            {
                path: "/account/information",
                name: "account.information",
                component: () => import("../pages/Account/Information.vue"),
            },
            {
                path: "/account/change-password",
                name: "account.password",
                component: () => import("../pages/Account/Password.vue"),
            },
            {
                path: "/account/2fa",
                name: "account.2fa",
                component: () => import("../pages/Account/2fa.vue"),
            },
            {
                path: "/developers/clients",
                name: "developer.clients.index",
                component: () => import("../pages/OAuth/Clients/Index.vue"),
            },
            {
                path: "/developers/api_key",
                name: "developer.api.index",
                component: () => import("../pages/OAuth/Personal/Index.vue"),
            },
        ],
    },

    {
        path: "/admin",
        component: () => import("../pages/AdminLayout.vue"),
        children: [
            {
                path: "users",
                name: "admin.users.index",
                component: () => import("../pages/Admin/Users/Index.vue"),
            },
            {
                path: "clients",
                name: "admin.clients.index",
                component: () => import("../pages/Admin/Clients/Index.vue"),
            },
            {
                path: "broadcasts",
                name: "admin.broadcasts.index",
                component: () => import("../pages/Admin/Broadcast/Index.vue"),
            },
            {
                path: "groups",
                name: "admin.groups.index",
                component: () => import("../pages/Admin/Groups/Index.vue"),
            },
            {
                path: "roles",
                name: "admin.roles.index",
                component: () => import("../pages/Admin/Role/Index.vue"),
            },
            {
                path: "services",
                name: "admin.services.index",
                component: () => import("../pages/Admin/Service/Index.vue"),
            },
        ],
    },

    {
        path: "/terminal",
        children: [
            {
                path: "",
                name: "terminal.index",
                component: () => import("../pages/TerminalLayout.vue"),
            },
        ],
    },
];

export const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});
