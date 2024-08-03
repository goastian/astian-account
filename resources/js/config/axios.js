import axios from "axios";
import https from "stream-http";

export const $server = axios.create({
    baseURL: process.env.MIX_APP_URL,
    timeout: 5000,
    withCredentials: true,
    httpsAgent: new https.Agent({ keepAlive: true }),
    headers: {
        Accept: "application/json",
    },

    validateStatus: function (status) {
        return status >= 200 && status < 300;
    },
});

$server.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response && error.response.status === 401) {
            window.location.href = "/login";
        }
        return Promise.reject(error);
    }
);
