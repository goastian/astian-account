<template>
    <ul class="nav">
        <li class="nav-item" @click="Expand">
            <a href="#" class="btn btn-primary"
                ><i class="bi bi-list h5"></i
            ></a>
        </li>
        <li class="nav-item ms-auto">
            <v-apps></v-apps>
        </li>

        <li class="nav-item dropdown">
            <a
                class="btn btn-primary dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            >
                <i class="bi bi-bell-fill h5" @click="unreadNotification"></i>
                <span
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                >
                    {{ unread_notifications.length }}
                    <span class="visually-hidden">unread messages</span>
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-notify">
                <li class="dropdown-item h5">
                    <router-link :to="{ name: 'notify.unread' }">
                        Ver todas
                        <span class="badge text-bg-danger">{{
                            unread_notifications.length
                        }}</span>
                    </router-link>
                </li>
                <li class="dropdown-divider"></li>
                <li
                    class="dropdown-item"
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
                        <p>
                            {{ item.mensaje }}
                        </p>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item dropdown">
            <a
                class="btn btn-primary dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            >
                <i class="bi bi-box-arrow-in-right h3"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                <li>
                    <a class="dropdown-item" href="#" @click="logout">Logout</a>
                </li>
            </ul>
        </li>
    </ul>
</template>
<script>
import VApps from "./Apps.vue";

export default {
    emits: ["expand"],

    components: {
        VApps,
    },

    data() {
        return {
            expand: false,
            notifications: {},
            unread_notifications: {},
        };
    },

    mounted() {
        window.addEventListener("resize", this.screenIsChanging);
        this.screenIsChanging();
        this.notification();
        this.unreadNotification();
        this.listenEvents();
    },

    methods: {
        Expand() {
            this.expand = !this.expand;
            this.$emit("expand", this.expand);
        },

        screenIsChanging() {
            this.expand = window.innerWidth < 940;
        },

        logout() {
            this.$server
                .post("logout")
                .then((res) => {
                    window.location.href = process.env.APP_URL;
                })
                .catch((err) => {
                    console.log(err);
                });
        },

        notification() {
            this.$server
                .get("api/notifications")
                .then((res) => {
                    this.notifications = res.data.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },

        unreadNotification() {
            this.$server
                .get("api/notifications/unread")
                .then((res) => {
                    this.unread_notifications = res.data.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },

        readNotification(link) {
            this.$server
                .post(link)
                .then((res) => {
                    this.notification();
                })
                .catch((err) => {
                    console.log(err);
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
