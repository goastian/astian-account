import axios from "axios";
import https from "stream-http";

export const $server = axios.create({
    timeout: 5000,
    withCredentials: true,
    httpsAgent: new https.Agent({ keepAlive: true }),
    headers: {
        "X-LOCALTIME": Intl.DateTimeFormat().resolvedOptions().timeZone,
        Accept: "application/json",
    },

    validateStatus: function (status) {
        return status >= 200 && status < 300;
    },
});
