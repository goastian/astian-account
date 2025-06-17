import { defineStore } from 'pinia';

const useThemeStore = defineStore('themeStore', {
    state: () => ({
        selectedTheme: {
            label: 'Light',
            value: 'light',
            icon: 'light_mode',
            color: 'primary',
        },
        themes: [
            {
                label: "Light",
                value: "light",
                icon: "light_mode",
                color: "primary",
            },
            {
                label: "Dark",
                value: "dark",
                icon: "dark_mode",
                color: "grey-8",
            },
            {
                label: "Red",
                value: "red",
                icon: "palette",
                color: "red",
            },
        ],
    }),

    actions: {
        loadTheme($q) {
            document.body.setAttribute("data-theme", this.selectedTheme.value);
            localStorage.setItem("theme", this.selectedTheme.value);
            $q.dark.set(this.selectedTheme.value === "dark");
        },

        changeTheme(select, $q) {
            this.selectedTheme = select;
            this.loadTheme($q);
        }
    },
    persist: true
});

export default useThemeStore;
