import { createApp } from "vue";
import App from "./App.vue";

import { router } from "./config/rutes.js";
import { components } from "./config/globalComponents";
import { $channels, $echo } from "./config/echo.js";
import { $server } from "./config/axios.js";

import * as bootstrap from "bootstrap";
import "./config/matomo.js";

//checking changes in the routes
router.beforeEach((to, from, next) => {
    //Checking if the user is authenticated
    if (to.meta.auth) {
        $server
            .get("/api/gateway/check-authentication")
            .then((res) => {
                next();
            })
            .catch((err) => {
                //if the user is not authenticated, redirect to the login
                window.location.href = "/login";
            });
    } else {
        //the user is not authenticated continue to the route
        next();
    }
});

/**
 * Loading application
 */
$server
    .get("/api/gateway/user")
    .then((res) => {
        //Global ID from the authenticated user
        window.$id = res.data.id; 
        
        //Loading App
        const app = createApp(App);

        app.config.globalProperties.$echo = $echo;
        app.config.globalProperties.$channels = $channels;
        app.config.globalProperties.$server = $server;

        app.use(router);

        components.forEach((index) => {
            app.component(index[0], index[1]);
        });

        app.mount("#app");
    })
    .catch((err) => {
        //redirect tyo the login if the user is no authenticated
        console.log("redirect to the login");
    });
