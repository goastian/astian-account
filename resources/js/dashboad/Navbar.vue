<template>
    <ul class="nav pt-2">
        <li class="nav-item" @click="Expand(status)">
            <a href="#" class="btn">
                <span class="text-light">
                    {{ app_name }}
                </span>
            </a>
            <i
                class="bi bi-list h5 mx-1 text-light"
                style="cursor: pointer"
            ></i>
        </li>
        <li class="nav-item ms-auto">
            <v-apps></v-apps>
        </li>

        <li class="nav-item dropdown">
            <a
                class="btn dropdown-toggle text-light"
                data-bs-toggle="dropdown"
                aria-expanded="true"
            >
                <i class="bi bi-bell-fill h5" @click="unreadNotification"></i>
                <span class="position-absolute badge rounded-pill bg-danger">
                    {{ unread_notifications.length }}
                    <span class="visually-hidden">Unread messages</span>
                </span>
            </a>
            <ul class="dropdown-menu expand">
                <li class="dropdown-item text-color">
                    <a :href="host + '/notifications/unread'">
                        Notifications
                        <span class="badge text-bg-danger">{{
                            unread_notifications.length
                        }}</span>
                    </a>
                </li>
                <li class="dropdown-divider"></li>
                <li
                    class="dropdown-item"
                    style="cursor: pointer"
                    v-for="(item, index) in unread_notifications"
                    :key="index"
                >
                    <a
                        class="text-sm text-color px-1"
                        :href="item.resource"
                        target="_blank"
                        @click="readNotification(item.links.read)"
                    >
                        {{ item.subject }}
                        <i
                            :class="{
                                'bi h5 mx-2': true,
                                'bi-eye': item.read,
                                'bi-eye-slash': !item.read,
                            }"
                        ></i>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item dropdown icon">
            <a
                class="btn dropdown-toggle text-light text-capitalize"
                data-bs-toggle="dropdown"
                aria-expanded="true"
            >
                {{ user.name }}
                <i class="bi bi-box-arrow-in-right m-0"></i>
            </a>
            <ul class="dropdown-menu expand">
                <li class="dropdown-item">
                    <a class="text-color" :href="host" target="_self"
                        ><i class="bi bi-house-lock mx-1"></i>
                        My Account
                    </a>
                </li>
                <li class="dropdown-divider"></li>
                <li class="dropdown-item">
                    <a class="text-color" @click="logout" href="#">
                        <i class="bi bi-lock-fill mx-1"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</template>
<script>
import VApps from "./Apps.vue";

export default {
    emits: ["expand"],

    props: ["status"],

    components: {
        VApps,
    },

    data() {
        return {
            expand: false,
            notifications: {},
            unread_notifications: {},
            host: process.env.MIX_APP_SERVER,
            app_name: process.env.MIX_APP_NAME,
            user: {},
        };
    },

    mounted() {
        window.addEventListener("resize", this.screenIsChanging);
        this.screenIsChanging();
        this.auth();
        this.listenEvents();
    },

    methods: {
        Expand(status = false) {
            this.expand = !status;
            this.$emit("expand", this.expand);
        },

        screenIsChanging() {
            this.expand = window.innerWidth < 940;
        },

        auth() {
            this.$server
                .get("/api/gateway/user")
                .then((res) => {
                    this.user = res.data;
                    this.notification();
                    this.unreadNotification();
                })
                .catch((err) => {
                    if (err.response && err.response.status == 401) {
                        console.log(err.response.data);
                    }
                });
        },

        logout() {
            this.$server
                .post("api/gateway/logout")
                .then((res) => {})
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response);
                    }
                });
        },

        notification() {
            this.$server
                .get("api/notifications")
                .then((res) => {
                    this.notifications = res.data.data;
                })
                .catch((err) => {
                    if (err.response && err.response.status == 401) {
                        console.log(err.response.data);
                    }
                });
        },

        unreadNotification() {
            this.$server
                .get("api/notifications/unread")
                .then((res) => {
                    this.unread_notifications = res.data.data;
                })
                .catch((err) => {
                    if (err.response && err.response.status == 401) {
                        console.log(err.response.data);
                    }
                });
        },

        readNotification(link) {
            this.$server
                .post(link)
                .then((res) => {
                    this.notification();
                })
                .catch((err) => {
                    if (err.response && err.response.status == 401) {
                        console.log(err.response.data);
                    }
                });
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("PushNotificationEvent", (e) => {
                    this.notification();
                    this.unreadNotification();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("ReadNotificationEvent", (e) => {
                    this.notification();
                    this.unreadNotification();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyNotificationEvent", (e) => {
                    this.notification();
                    this.unreadNotification();
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.nav {
    background-color: var(--nav-top-bg) !important;
    color: var(--nav-top-color) !important;
}
.expand {
    padding: 5% 30% 7% 0% !important;
}
.dropdown-item a {
    text-decoration: none;
    color: var(--dark);
}

.dropdown-item a:hover {
    text-decoration: dotted !important;
    color: var(--primary);
}

.nav-item {
    @media (min-width: 240px) {
        margin-top: 0%;
    }

    @media (min-width: 240px) {
        margin-right: 2%;
    }
}

.dropdown-item img {
    width: 15%;
}
</style>
