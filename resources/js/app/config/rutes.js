import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        component: () => import("../pages/AuthLayout.vue"),
        children: [
            {
                path: "about",
                name: "about",
                component: () => import("../pages/Account/About.vue"),
            },

            {
                path: "admin",
                component: () => import("../pages/AdminLayout.vue"),
                children: [
                    {
                        path: "users",
                        name: "admin.users.index",
                        component: () =>
                            import("../pages/Admin/Users/Index.vue"),
                    },
                    {
                        path: "clients",
                        name: "admin.clients.index",
                        component: () =>
                            import("../pages/Admin/Clients/Index.vue"),
                    },
                    {
                        path: "broadcasts",
                        name: "admin.broadcasts.index",
                        component: () =>
                            import("../pages/Admin/Broadcast/Index.vue"),
                    },
                    {
                        path: "roles",
                        name: "admin.roles.index",
                        component: () =>
                            import("../pages/Admin/Role/Index.vue"),
                    },
                    {
                        path: "services",
                        name: "admin.services.index",
                        component: () =>
                            import("../pages/Admin/Service/Index.vue"),
                    },
                    {
                        path: "scopes",
                        name: "admin.scopes.index",
                        component: () =>
                            import("../pages/Admin/Scope/Index.vue"),
                    },
                ],
            },

            {
                path: "developers",
                component: () => import("../pages/DeveloperLayout.vue"),
                children: [
                    {
                        path: "clients",
                        name: "developer.clients.index",
                        component: () =>
                            import("../pages/OAuth/Clients/Index.vue"),
                    },
                    {
                        path: "api_key",
                        name: "developer.api.index",
                        component: () =>
                            import("../pages/OAuth/Personal/Index.vue"),
                    },
                ],
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
