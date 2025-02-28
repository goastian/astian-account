import { createApp } from "vue";
import App from "./app/App.vue";
import Terminal from "./terminal/Terminal.vue";

import { router } from "./app/config/rutes.js";
import { customComponents } from "./app/config/customComponents.js";
import { $channels, $echo } from "./app/config/echo.js";
import { $server } from "./app/config/axios.js";
import { $customElement } from "./app/config/customElements.js";
import { notyf } from "./app/config/notification.js";

//Vue date picker
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";


//Import vuetify components
import "@mdi/font/css/materialdesignicons.css";
import "vuetify/styles";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";

const vuetify = createVuetify({
    components,
    directives,
});

//------ Admin app ----------------------------
const app = createApp(App);

app.config.globalProperties.$echo = $echo;
app.config.globalProperties.$channels = $channels;
app.config.globalProperties.$server = $server;
app.config.globalProperties.$ce = $customElement;
app.config.globalProperties.$notification = notyf;
app.use(router);
app.use(vuetify);
app.component('VueDatePicker', VueDatePicker);
/**
 * Custom components
 */
customComponents.forEach((index) => {
    app.component(index[0], index[1]);
});

//Mount app
app.mount("#app");

//--------------end admin app----------------

//-------- Start terminal app -----------------
const terminal = createApp(Terminal);

terminal.config.globalProperties.$server = $server;
terminal.config.globalProperties.$notification = notyf;
terminal.use(vuetify);

customComponents.forEach((index) => {
    terminal.component(index[0], index[1]);
});

terminal.mount("#terminal");
//-------- end terminal app -----------------
