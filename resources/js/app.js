import "./bootstrap";
import "../scss/app.scss";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import { router } from "./Pages/config/rutes.js";
import { components } from "./Pages/config/globalComponents";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/Dashboard.vue")
        ),
    setup({ el, App, props, plugin }) {
        app = createApp({ render: () => h(App, props) });

        app.use(plugin);
        app.use(router);
        app.use(ZiggyVue, Ziggy);

        components.forEach((index) => {
            app.component(index[0], index[1]);
        });

        return app.mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
