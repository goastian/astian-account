import Echo from "laravel-echo";
import io from "socket.io-client";

window.io = io;

const options = {
    broadcaster: "socket.io",
    host: import.meta.env.VITE_APP_LARAVEL_ECHO,
    transports: ["websocket", "polling", "flashsocket"],
};

export const $echo = new Echo(options);

export const $channels = {
    ch_0() {
        return import.meta.env.VITE_APP_LARAVEL_ECHO_CHANNEL;
    },

    ch_1(id) {
        return import.meta.env.VITE_APP_LARAVEL_ECHO_CHANNEL + `.${id}`;
    },
};
