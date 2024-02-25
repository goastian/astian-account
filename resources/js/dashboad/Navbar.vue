<template>
    <ul class="nav bg-primary">
        <li class="nav-item" @click="Expand(status)">
            <a href="#" class="btn btn-primary">
                <span class="mx-2">
                    {{ app_name }}
                </span>

                <i class="bi bi-list h5"></i
            ></a>
        </li>
        <li class="nav-item ms-auto">
            <v-apps></v-apps>
        </li>

        <li class="nav-item dropdown">
            <a
                class="btn btn-primary dropdown-toggle"
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
                <li class="dropdown-item h5">
                    <a href="/notifications/unread">
                        Notifications
                        <span class="badge text-bg-danger">{{
                            unread_notifications.length
                        }}</span>
                    </a>
                </li>
                <li class="dropdown-divider"></li>
                <li
                    class="dropdown-item p-0"
                    v-for="(item, index) in unread_notifications"
                    :key="index"
                >
                    <a
                        :href="item.recurso"
                        target="_blank"
                        @click="readNotification(item.links.read)"
                    >
                        <strong class=""
                            >{{ item.titulo }}
                            <i
                                :class="[
                                    'bi h5 mx-2',
                                    item.leido ? 'bi-eye' : 'bi-eye-slash',
                                ]"
                            ></i>
                        </strong>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item dropdown">
            <a
                class="btn btn-primary dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="true"
            >
                {{ user.nombre }}
                <i class="bi bi-box-arrow-in-right h4 m-0"></i>
            </a>
            <ul class="dropdown-menu expand bg-light">
                <li class="dropdown-item">
                    <a @click="logout" href="#">
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
            app_name: process.env.MIX_APP_NAME,
            expand: false,
            notifications: {},
            unread_notifications: {},
            user: {},
        };
    },

    mounted() {
        window.addEventListener("resize", this.screenIsChanging);
        this.screenIsChanging();
        this.listenEvents();
        this.auth();
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
                .post("/api/gateway/logout")
                .then((res) => {
                    //window.location.href = "/"
                })
                .catch((err) => {});
        },

        notification() {
            this.$server
                .get("api/notifications")
                .then((res) => {
                    this.notifications = res.data.data;
                })
                .catch((err) => {});
        },

        unreadNotification() {
            this.$server
                .get("api/notifications/unread")
                .then((res) => {
                    this.unread_notifications = res.data.data;
                })
                .catch((err) => {});
        },

        readNotification(link) {
            this.$server
                .post(link)
                .then((res) => {
                    this.notification();
                })
                .catch((err) => {});
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
    padding-top: 0.5%;
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
        margin-right: 0.5%;
    }

    @media (min-width: 800px) {
        margin-right: 2%;
    }
}

.dropdown-item img {
    width: 15%;
}
</style>
