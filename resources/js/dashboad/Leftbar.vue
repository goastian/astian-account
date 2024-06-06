<template>
    <aside class="leftbar" id="leftbar">
        <div class="head" v-if="!large_devices">
            <p @click="showMenu">
                <i
                    :class="{
                        bi: true,
                        'bi-three-dots-vertical': !menu_collapse,
                        'bi-three-dots': menu_collapse,
                    }"
                ></i>
            </p>
        </div>

        <ul class="menu" v-if="!small_devices">
            <li class="item">
                <a href="#" @click.prevent="showMenu">
                    <i class="bi bi-house-gear"></i>
                    <span v-if="menu_collapse">Dashboard</span>
                </a>
                <ul v-if="menu_collapse" class="sub-menu">
                    <li>
                        <router-link
                            :to="{ name: 'home' }"
                            @click="isSmallDevices"
                        >
                            <i class="bi bi-person-circle"></i>
                            <span> Profile </span>
                        </router-link>
                    </li>
                    <li v-show="user_can.users">
                        <router-link
                            :to="{ name: 'users' }"
                            @click="isSmallDevices"
                        >
                            <i class="bi bi-people"></i> <span> Users </span>
                        </router-link>
                    </li>
                    <li v-show="user_can.roles">
                        <router-link
                            :to="{ name: 'scopes' }"
                            @click="isSmallDevices"
                        >
                            <i class="bi bi-shield-shaded"></i>
                            <span> Roles </span>
                        </router-link>
                    </li>
                    <li v-show="user_can.broadcast">
                        <router-link
                            :to="{ name: 'channels' }"
                            @click="isSmallDevices"
                        >
                            <i class="bi bi-broadcast"></i>
                            <span> Broadcast </span>
                        </router-link>
                    </li>
                </ul>
            </li>
            <li class="item">
                <a href="#" @click.prevent="showMenu">
                    <i class="bi bi-hdd-stack"></i>
                    <span v-if="menu_collapse">Micro Services</span>
                </a>

                <ul v-if="menu_collapse" class="sub-menu">
                    <li>
                        <router-link
                            :to="{ name: 'clients' }"
                            @click="isSmallDevices"
                        >
                            <i class="bi bi-window-dock"></i>
                            <span> Clients </span>
                        </router-link>
                    </li>
                    <li>
                        <router-link
                            :to="{ name: 'tokens' }"
                            @click="isSmallDevices"
                        >
                            <i class="bi bi-filetype-key"></i>
                            <span> Sessions </span>
                        </router-link>
                    </li>
                    <li>
                        <router-link
                            :to="{ name: 'personalTokens' }"
                            @click="isSmallDevices"
                        >
                            <i class="bi bi-hdd-network-fill"></i>
                            <span> Tokens </span>
                        </router-link>
                    </li>
                </ul>
            </li>
            <li class="item">
                <a href="#" @click.prevent="showMenu">
                    <i class="bi bi-bell"></i>
                    <span v-if="menu_collapse">Notifications</span>
                </a>

                <ul v-if="menu_collapse" class="sub-menu">
                    <li v-show="user_can.notification">
                        <router-link
                            :to="{ name: 'notify' }"
                            @click="isSmallDevices"
                        >
                            <i class="bi bi-send"></i> <span> Push </span>
                        </router-link>
                    </li>
                    <li>
                        <router-link
                            :to="{ name: 'notify.read' }"
                            @click="isSmallDevices"
                        >
                            <i class="bi bi-bell-fill"></i> <span> All </span>
                        </router-link>
                    </li>
                    <li>
                        <router-link
                            :to="{ name: 'notify.unread' }"
                            @click="isSmallDevices"
                        >
                            <i class="bi bi-bell-slash-fill"></i>
                            <span> Unread </span>
                        </router-link>
                    </li>
                </ul>
            </li>
            <li class="item">
                <a href="#" @click.prevent="showMenu">
                    <i class="bi bi-gear-wide-connected"></i>
                    <span v-if="menu_collapse">Settings</span>
                </a>
                <ul v-if="menu_collapse" class="sub-menu">
                    <li>
                        <router-link
                            :to="{ name: 'security' }"
                            @click="isSmallDevices"
                        >
                            <i class="bi bi-lock"></i> <span> Security </span>
                        </router-link>
                    </li>
                    <li>
                        <router-link
                            :to="{ name: 'devices' }"
                            @click="isSmallDevices"
                        >
                            <i class="bi bi-laptop"></i>
                            <span> Devices </span>
                        </router-link>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</template>
<script>
export default {
    data() {
        return {
            user_can: {},
            menu_collapse: false,
            small_devices: false,
            large_devices: true,
        };
    },

    created() {
        this.userCan();
    },

    mounted() {
        window.addEventListener("resize", this.isSmallDevices);
        this.isSmallDevices();

        document.addEventListener("click", this.hideMenu);
    },

    methods: {
        showMenu() {
            this.menu_collapse = !this.menu_collapse;
            if (window.innerWidth < 800) {
                this.small_devices = !this.small_devices;
            }
        },

        hideMenu(event) {
            const menu = document.getElementById("leftbar");
            const body = document.getElementsByTagName("body")[0];

            if (event.target !== menu && !menu.contains(event.target)) {
                this.menu_collapse = false;
            }
        },

        isSmallDevices() {
            if (window.innerWidth < 800) {
                this.small_devices = true;
                this.large_devices = false;
            } else {
                this.small_devices = false;
                this.large_devices = true;
            }
        },

        userCan() {
            this.$server
                .get("/api/gateway/user")
                .then((res) => {
                    this.user_can = res.data.access;
                })
                .catch((err) => {});
        },
    },
};
</script>

<style lang="scss" scoped>
.leftbar {
    background-color: var(--white);
    border: 1px solid var(--border-color-light);
    border-radius: 0.3em;
    position: fixed;
    top: 0;
    left: 0;
    width: fit-content;

    @media (min-width: 800px) {
        position: relative;
        top: 0;
        left: 0;
    }

    .head {
        padding: 0 0 0 0em;

        p {
            opacity: 1 !important;
            color: var(--first-color);
            margin: 0 0.2em;

            i {
                font-size: 1.2em;
            }
        }
    }
    .menu {
        margin-top: 0.5em;
        padding: 0 0.2em;
        list-style: none;

        .item {
            color: var(--first-color);
            padding-right: 0.5em;
            margin-bottom: 0.5em;

            a {
                width: 100%;
                padding: 0.1em 0.5em;
                display: flex;
                flex-wrap: nowrap;
                color: var(--first-color);
                font-weight: bold;
                text-decoration: none;

                i {
                    font-size: 1em;
                }
                span {
                    font-weight: bold;
                    margin-left: 0.4em;
                    font-size: 0.8em;
                }

                &:hover {
                    color: var(--secondary);
                }
            }

            .sub-menu {
                padding: 0 1em;
                list-style: none;
                li {
                    a {
                        color: var(--first-color);
                        text-decoration: none;
                        font-size: 0.9em;
                    }

                    span,i {
                        &:hover {
                            color: var(--primary);
                        }
                    }
                }
            }
        }
    }
}
</style>
