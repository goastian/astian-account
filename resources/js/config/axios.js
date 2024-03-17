import axios from "axios";

export const $server = axios.create({
    baseURL: process.env.MIX_APP_URL,
    timeout: 5000,
    withCredentials: true,
    responseEncoding: "utf8",
    headers: {
        Accept: "application/json",
        "X-LOCALTIME": Intl.DateTimeFormat().resolvedOptions().timeZone,
    },
});
