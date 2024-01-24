import { createApp } from "vue";
import App from "./App.vue";

import { router } from "./config/rutes.js";
import { components } from "./config/globalComponents";
import { $channels, $echo } from "./config/echo.js";
import { $server } from './config/axios.js'

import "./config/axios.js";  
import * as bootstrap from 'bootstrap'

const app = createApp(App);

app.config.globalProperties.$echo = $echo;
app.config.globalProperties.$channels = $channels;
app.config.globalProperties.$server = $server

app.use(router);

components.forEach((index) => {
    app.component(index[0], index[1]);
});

app.mount("#app");
