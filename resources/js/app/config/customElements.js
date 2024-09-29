import { ElNotification } from "element-plus";

export const $customElement = {
    /**
     * Show a notification types (success,warning,info,error)
     * @param {*} title
     * @param {*} message
     * @param {*} type
     */
    notification(title, message, type, duration = 5000) {
        ElNotification({
            title: title,
            message: message,
            type: type,
            duration: duration,
        });
    },

    /**
     * Change theme
     * @param {*} theme
     */
    changeTheme(theme) {
        var html = document.getElementById("html");

        switch (theme) {
            case "dark":
                html.classList.add("dark");
                break;

            default:
                html.classList.remove("dark");
                break;
        }
    },

    /**
     * Cange font size
     * @param {*} font_size
     */
    changeFontSize(font_size) {
        document.documentElement.style.setProperty("--font-size", font_size);
    },

    /**
     * Change font family
     * @param {*} font_family
     */
    changeFontFamily(font_family) {
        document.documentElement.style.setProperty(
            "--font-family",
            font_family
        );
    },

    /**
     * Added the nonce policy in the styles
     *
     * @param {*} nonce
     */
    addStyleNoncePolicy(nonce) {
        const tag = document.querySelector("style");
        if (tag) {
            tag.setAttribute("nonce", nonce);
        }
    },
};
