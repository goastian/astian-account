import { createApp } from "vue";

import App from "./app/App.vue";

import { router } from "./app/config/rutes.js";
import { customComponents } from "./app/config/customComponents.js";
import { $channels, $echo } from "./app/config/echo.js";
import { $server } from "./app/config/axios.js";

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

//------ Admin app ----------------------------
const app = createApp(App);

app.config.globalProperties.$echo = $echo;
app.config.globalProperties.$channels = $channels;
app.config.globalProperties.$server = $server;
app.use(router);
app.component("VueDatePicker", VueDatePicker);
/**
 * Custom components
 */
customComponents.forEach((index) => {
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

//Mount app
app.mount("#app");
