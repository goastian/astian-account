import { createApp } from "vue";
import App from "./App.vue";

import { router } from "./config/rutes.js";
import { components } from "./config/globalComponents";
import { $channels, $echo } from "./config/echo.js";

import "./bootstrap";
import "../scss/app.scss";

const app = createApp(App);
app.config.globalProperties.$echo = $echo;
app.config.globalProperties.$channels = $channels;
app.use(router);

components.forEach((index) => {
    app.component(index[0], index[1]);
});

app.mount("#app");
