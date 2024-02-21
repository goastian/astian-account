import { createApp } from "vue";
import App from "./App.vue";

import { router } from "./config/rutes.js";
import { components } from "./config/globalComponents";
import { $channels, $echo } from "./config/echo.js";
import { $server } from "./config/axios.js";

import * as bootstrap from "bootstrap";
import "./config/matomo.js";

//checking the routes
router.beforeEach((to, from, next) => {
    //Checking if the user is authenticated
    if (to.meta.auth) {
        $server
            .get("/api/gateway/user")
            .then((res) => {
                window.$auth = res.data; //global key
                next();
            })
            .catch((err) => {
                //if the user is not authenticated, redirect to the login
                window.location.href = "/login";
            });
    } else {
        next();
    }
});

const app = createApp(App);

app.config.globalProperties.$echo = $echo;
app.config.globalProperties.$channels = $channels;
app.config.globalProperties.$server = $server;

app.use(router);

components.forEach((index) => {
    app.component(index[0], index[1]);
});

app.mount("#app");
