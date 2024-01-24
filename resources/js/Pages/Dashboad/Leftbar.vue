<template>
    <aside class="side">
        <div class="head py-3">
            <a href="#">
                <img
                    src="../../../img/fuentes.svg"
                    alt="astian font"
                    class="title mx-4"
                />
                <img
                    src="../../../img/favicon.svg"
                    alt="astian icon"
                    class="icon"
                />
            </a>
        </div>
        <div class="menu">
            <ul>
                <li class="sub-menu">
                    Dashboard
                    <ul class="sub-menu">
                        <li>
                            <router-link
                                :to="{ name: 'profile' }"
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
                                <i class="bi bi-hdd-network-fill"></i> API
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
                                <i class="bi bi-send mx-2"></i> Push
                            </router-link>
                        </li>
                        <li>
                            <router-link
                                :to="{ name: 'notify.read' }"
                                @click="screenIsChanging"
                            >
                                <i class="bi bi-bell-fill mx-2"></i> All
                            </router-link>
                        </li>
                        <li>
                            <router-link
                                :to="{ name: 'notify.unread' }"
                                @click="screenIsChanging"
                            >
                                <i class="bi bi-bell-slash-fill mx-2"></i>
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
                                <i class="bi bi-lock mx-2"></i> Security
                            </router-link>
                        </li>
                        <li>
                            <router-link
                                :to="{ name: 'devices' }"
                                @click="screenIsChanging"
                            >
                                <i class="bi bi-laptop mx-2"></i> Devices
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
    emits: ["selectMenu"],

    mounted() {
        window.addEventListener("resize", this.screenIsChanging);
        this.screenIsChanging();
    },

    methods: {
        screenIsChanging() {
            if (window.innerWidth < 940) {
                this.$emit("selectMenu", window.innerWidth < 940);
            }
        },
    },
};
</script>
