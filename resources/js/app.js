import { createApp } from "vue";
import App from "./app/App.vue";

import { router } from "./app/config/rutes.js";
import { components } from "./app/config/globalComponents";
import { $channels, $echo } from "./app/config/echo.js";
import { $server } from "./app/config/axios.js";

import ElementPlus from "element-plus";
import "element-plus/dist/index.css";
import * as ElementPlusIconsVue from "@element-plus/icons-vue";

import "./app/config/matomo.js";

try {
    const res = await $server.get("/api/gateway/user");
    /**
     * Montamos la vue app solo cuando se haya authenticado el usuario
     */
    if (res.status == 200) {
        const app = createApp(App);
        for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
            app.component(key, component);
        }

        app.config.globalProperties.$user = res.data;
        app.config.globalProperties.$id = res.data.id;
        app.config.globalProperties.$echo = $echo;
        app.config.globalProperties.$channels = $channels;
        app.config.globalProperties.$server = $server;
        app.use(router);
        app.use(ElementPlus);
        /**
         * Global components
         */
        components.forEach((index) => {
            app.component(index[0], index[1]);
        });

        app.mount("#app");
    }
} catch (error) {}
