import axios from "axios";
 
export const $server = axios.create({
    baseURL: process.env.MIX_APP_URL,
    timeout: 5000,
    withCredentials: true,
    headers: {
        'X-Requested-With':'XMLHttpRequest',
        Accept: "application/json",
    },
});
 