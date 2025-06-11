import axios from "axios";
import https from "stream-http";

export const $server = axios.create({
    timeout: 5000,
    withCredentials: true,
    httpsAgent: new https.Agent({ keepAlive: true }),
    headers: {
        "X-LOCALTIME": Intl.DateTimeFormat().resolvedOptions().timeZone,
        Accept: "application/json",
        "X-Socket-ID": window.$echo.socket_id,
    },

    validateStatus: function (status) {
        return status >= 200 && status < 300;
    },
});

$server.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response && error.response.status === 401) {
            //  window.location.href = "/login";
        }
        return Promise.reject(error);
    }
);
