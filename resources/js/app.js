import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";

import { customComponents } from "./app/config/customComponents.js";
import { $echo } from "./app/config/echo.js";
import { $server } from "./app/config/axios.js";
import { layouts } from "./app/config/layouts.js";

//Quasar
import { Quasar, Ripple, ClosePopup, Notify, Dialog, Loading } from "quasar";
import "quasar/dist/quasar.css";
import "@quasar/extras/material-icons/material-icons.css";
import { QComponents } from "./app/config/quasar.js";

//Vue date picker
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

//icons https://pictogrammers.com/library/mdi/
import "@mdi/font/css/materialdesignicons.css";

createInertiaApp({
    resolve: (name) => {
        const pages = require.context("./app/pages", true, /\.vue$/);
        return pages(`./${name}.vue`).default;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        customComponents.forEach((index) => {
            app.component(index[0], index[1]);
        });

        layouts.forEach((index) => {
            app.component(index[0], index[1]);
        });

        app.use(Quasar, {
            plugins: {
                Notify,
                Dialog,
                Loading,
            },
            directives: {
                Ripple,
                ClosePopup,
            },
        });

        QComponents.forEach((item) => {
            app.component(item.name, item);
        });

        app.config.globalProperties.$echo = $echo; 
        app.config.globalProperties.$server = $server;

        app.component("VueDatePicker", VueDatePicker);
        app.use(plugin);
        app.mount(el);
    },
});
