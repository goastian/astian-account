<template>
    <aside class="side bg-light">
        <div class="menu text-color">
            <ul>
                <li class="sub-menu">
                    Dashboard
                    <ul class="sub-menu">
                        <li>
                            <router-link
                                :to="{ name: 'home' }"
                                @click="screenIsChanging"
                            >
                                <i class="bi bi-person-circle"></i> Profile
                            </router-link>
                        </li>
                        <li>
                            <router-link
                                :to="{ name: 'users' }"
                                @click="screenIsChanging"
                            >
                                <i class="bi bi-people"></i> Users
                            </router-link>
                        </li>
                        <li>
                            <router-link
                                :to="{ name: 'scopes' }"
                                @click="screenIsChanging"
                            >
                                <i class="bi bi-shield-shaded"></i> Roles
                            </router-link>
                        </li>
                        <li>
                            <router-link
                                :to="{ name: 'channels' }"
                                @click="screenIsChanging"
                            >
                                <i class="bi bi-broadcast"></i> Broadcast
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li class="sub-menu">
                    Micro Services
                    <ul class="sub-menu">
                        <li>
                            <router-link
                                :to="{ name: 'clients' }"
                                @click="screenIsChanging"
                            >
                                <i class="bi bi-window-dock"></i> Clients
                            </router-link>
                        </li>
                        <li>
                            <router-link
                                :to="{ name: 'tokens' }"
                                @click="screenIsChanging"
                            >
                                <i class="bi bi-filetype-key"></i> Sessions
                            </router-link>
                        </li>
                        <li>
                            <router-link
                                :to="{ name: 'personalTokens' }"
                                @click="screenIsChanging"
                            >
                                <i class="bi bi-hdd-network-fill"></i> Tokens
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li class="sub-menu">
                    Notifications
                    <ul class="sub-menu">
                        <li>
                            <router-link
                                :to="{ name: 'notify' }"
                                @click="screenIsChanging"
                            >
                                <i class="bi bi-send"></i> Push
                            </router-link>
                        </li>
                        <li>
                            <router-link
                                :to="{ name: 'notify.read' }"
                                @click="screenIsChanging"
                            >
                                <i class="bi bi-bell-fill"></i> All
                            </router-link>
                        </li>
                        <li>
                            <router-link
                                :to="{ name: 'notify.unread' }"
                                @click="screenIsChanging"
                            >
                                <i class="bi bi-bell-slash-fill"></i>
                                Unread
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li class="sub-menu">
                    Settings
                    <ul class="sub-menu">
                        <li>
                            <router-link
                                :to="{ name: 'security' }"
                                @click="screenIsChanging"
                            >
                                <i class="bi bi-lock"></i> Security
                            </router-link>
                        </li>
                        <li>
                            <router-link
                                :to="{ name: 'devices' }"
                                @click="screenIsChanging"
                            >
                                <i class="bi bi-laptop"></i> Devices
                            </router-link>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </aside>
</template>
<script>
export default {
    emits: ["selectedMenu"],

    mounted() {
        window.addEventListener("resize", this.screenIsChanging);
        this.screenIsChanging();
    },

    methods: {
        screenIsChanging() {
            if (window.innerWidth < 940) {
                this.$emit("selectedMenu", window.innerWidth < 940);
            }
        },
    },
};
</script>

<style lang="scss" scoped>
.side ul {
    padding: 0;
    margin: 0;
}

.side li {
    list-style: none;
    padding: 0;
    margin: 0;
}

.side {
    width: 100%;
    height: 100vh;
}

.side .menu {
    margin-left: 1%;
}

.side .sub-menu {
    margin-left: 2%;
    padding: 2%;
}

.side .sub-menu li {
    padding-bottom: 0.4%;
    font-weight: normal;
    margin-left: 2%;
}

a {
    text-decoration: none;
}
</style>
