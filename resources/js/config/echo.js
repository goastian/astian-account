import EchoClient from "echo-client-js";

const options = { 
    host: import.meta.env.VITE_APP_ECHO_SERVER,
    port: import.meta.env.VITE_APP_ECHO_SERVER_PORT,
    transport: import.meta.env.VITE_APP_ECHO_SERVER_PROTOCOL,
};
 
export const $echo = new EchoClient(options);

export const $channels = {
    ch_0() {
        return import.meta.env.VITE_APP_LARAVEL_ECHO_CHANNEL;
    },

    ch_1(id) {
        return `${import.meta.env.VITE_APP_LARAVEL_ECHO_CHANNEL}.${id}`;
    },
};