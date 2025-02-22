import { createApp } from "vue";
import App from "./app/App.vue";

import { router } from "./app/config/rutes.js";
import { customComponents } from "./app/config/customComponents.js";
import { $channels, $echo } from "./app/config/echo.js";
import { $server } from "./app/config/axios.js";
import { $customElement } from "./app/config/customElements.js";
import { notyf } from "./app/config/notification.js";

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

//Setting vuejs App
const app = createApp(App);

app.config.globalProperties.$echo = $echo;
app.config.globalProperties.$channels = $channels;
app.config.globalProperties.$server = $server;
app.config.globalProperties.$ce = $customElement;
app.config.globalProperties.$notification = notyf;
app.use(router);
app.use(vuetify);

/**
 * Custom components
 */
customComponents.forEach((index) => {
    app.component(index[0], index[1]);
});

//Mount app
app.mount("#app");
