import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        component: () => import("../pages/GuestLayout.vue"),
        children: [
            {
                path: "/plans",
                name: "plans.index",
                component: () => import("../pages/Resources/Plan.vue"),
                meta: { auth: false },
            },
        ],
    },
    {
        path: "/account",
        component: () => import("../pages/UserLayout.vue"),
        children: [
            {
                path: "",
                name: "account.me",
                component: () => import("../pages/Account/About.vue"),
                meta: { auth: true },
            },

            {
                path: "/account/information",
                name: "account.information",
                component: () => import("../pages/Account/Information.vue"),
                meta: { auth: true },
            },
            {
                path: "/account/change-password",
                name: "account.password",
                component: () => import("../pages/Account/Password.vue"),
                meta: { auth: true },
            },
            {
                path: "/account/2fa",
                name: "account.2fa",
                component: () => import("../pages/Account/2fa.vue"),
                meta: { auth: true },
            },
            {
                path: "/account/subscriptions",
                name: "account.subscriptions",
                component: () =>
                    import("../pages/Account/Subscription/Index.vue"),
                meta: { auth: true },
            },
            {
                path: "/developers/clients",
                name: "developer.clients.index",
                component: () => import("../pages/OAuth/Clients/Index.vue"),
                meta: { auth: true },
            },
            {
                path: "/developers/api_key",
                name: "developer.api.index",
                component: () => import("../pages/OAuth/Personal/Index.vue"),
                meta: { auth: true },
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
                meta: { auth: true },
            },
            {
                path: "clients",
                name: "admin.clients.index",
                component: () => import("../pages/Admin/Clients/Index.vue"),
                meta: { auth: true },
            },
            {
                path: "broadcasts",
                name: "admin.broadcasts.index",
                component: () => import("../pages/Admin/Broadcast/Index.vue"),
                meta: { auth: true },
            },
            {
                path: "groups",
                name: "admin.groups.index",
                component: () => import("../pages/Admin/Groups/Index.vue"),
                meta: { auth: true },
            },
            {
                path: "roles",
                name: "admin.roles.index",
                component: () => import("../pages/Admin/Role/Index.vue"),
                meta: { auth: true },
            },
            {
                path: "plans",
                name: "admin.plans.index",
                component: () => import("../pages/Admin/Plans/Index.vue"),
                meta: { auth: true },
            },
            {
                path: "services",
                name: "admin.services.index",
                component: () => import("../pages/Admin/Service/Index.vue"),
                meta: { auth: true },
            },
            {
                path: "transactions",
                name: "admin.transactions.index",
                component: () => import("../pages/Admin/Transaction/Index.vue"),
                meta: { auth: true },
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
                meta: { auth: true },
            },
        ],
    },
];

export const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});
