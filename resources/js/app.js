import { createApp } from "vue";
import App from "./App.vue";

import { router } from "./config/rutes.js";
import { components } from "./config/globalComponents";
import { $channels, $echo } from "./config/echo.js";
import { $server } from "./config/axios.js";
 
import "./config/matomo.js";

//checking changes in the routes
router.beforeEach((to, from, next) => {
    /**
     * Chechin the auth routes
     */
    if (to.meta.auth) {
        $server
            .get("/api/gateway/check-authentication")
            .then((res) => {
                next();
            })
            .catch((err) => {
                window.location.href = "/login";
            });
    } else {
        /**
         * Cheking no auth routes and redirect if the user is in login view to home route
         * in another case go to the route
         */
        if (to.path === "/login") {
            $server
                .get("/api/gateway/check-authentication")
                .then((res) => {
                    return next({ name: "home" });
                })
                .catch((err) => {});
        } else {
            next();
        }
    }
});

//Loading App
const app = createApp(App);
/**
 * Get the user id
 */
$server
    .get("/api/gateway/user")
    .then((res) => {
        app.config.globalProperties.$id = res.data.id;
    })
    .catch((err) => {});

app.config.globalProperties.$echo = $echo;
app.config.globalProperties.$channels = $channels;
app.config.globalProperties.$server = $server;

app.use(router);

/**
 * Global components
 */
components.forEach((index) => {
    app.component(index[0], index[1]);
});

app.mount("#app");
