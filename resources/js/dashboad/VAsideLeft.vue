<template>
    <el-menu :collapse="collapse" style="min-height: 100vh">
        <el-sub-menu index="1">
            <template #title>
                <el-icon><UserFilled /></el-icon>
                <span>Dashboard</span>
            </template>
            <el-menu-item @click="goUsers">Users</el-menu-item>
            <el-menu-item @click="goRoles">Roles</el-menu-item>
            <el-menu-item @click="goBroadcasts">Broadcast</el-menu-item>
            <el-menu-item @click="goSecurity">Securiry</el-menu-item>
        </el-sub-menu>
        <el-sub-menu index="2">
            <template #title>
                <el-icon><Tools /></el-icon>
                <span>Services</span>
            </template>
            <el-menu-item @click="goClients">Clients</el-menu-item>
            <el-menu-item @click="goCredentials">Credentials</el-menu-item>
            <el-menu-item @click="goTokenGenerated"
                >Tokens Generated</el-menu-item
            >
        </el-sub-menu>
        <el-sub-menu index="3">
            <template #title>
                <el-badge :value="unread_notifications.length" class="item">
                    <el-icon><BellFilled /></el-icon>
                </el-badge>
            </template>
            <el-menu-item @click="goPushNotifications">Send</el-menu-item>
            <el-menu-item @click="goAllNotifications">
                All
                <el-text
                    v-show="notifications.length"
                    class="mx-1"
                    type="primary"
                >
                    ( {{ notifications.length }} )
                </el-text>
            </el-menu-item>
            <el-menu-item @click="goUnreadNotifications">
                Unread
                <el-text
                    v-show="unread_notifications.length"
                    class="mx-1"
                    type="danger"
                >
                    ({{ unread_notifications.length }})
                </el-text>
            </el-menu-item>
        </el-sub-menu>
        <el-menu-item @click="logout">
            <el-popover
                placement="right"
                title="Logout"
                :width="200"
                trigger="hover"
                content="Click the icon to close the session"
            >
                <template #reference>
                    <el-icon @click.prevent="logout"><Lock /></el-icon>
                </template>
            </el-popover>
        </el-menu-item>
    </el-menu>
</template>
<script>
export default {
    props: {
        collapse: {
            type: Boolean,
            required: false,
            default: true,
        },
    },

    data() {
        return {
            notifications: {},
            unread_notifications: {},
            user: {},
        };
    },

    mounted() {
        this.unreadNotification();
        this.notification();
        this.listenEvents();
    },

    methods: {
        goUsers() {
            this.$router.push({ name: "users" });
        },

        goRoles() {
            this.$router.push({ name: "scopes" });
        },

        goBroadcasts() {
            this.$router.push({ name: "channels" });
        },

        goSecurity() {
            this.$router.push({ name: "security" });
        },

        goClients() {
            this.$router.push({ name: "clients" });
        },

        goCredentials() {
            this.$router.push({ name: "personalTokens" });
        },
        goTokenGenerated() {
            this.$router.push({ name: "tokens" });
        },
        goPushNotifications() {
            this.$router.push({ name: "notify" });
        },
        goAllNotifications() {
            this.$router.push({ name: "notify.read" });
        },
        goUnreadNotifications() {
            this.$router.push({ name: "notify.unread" });
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
                    window.location.href = "/login"
                })
                .catch((err) => {});
        },

        notification() {
            this.$server
                .get("/api/notifications")
                .then((res) => {
                    this.notifications = res.data.data;
                })
                .catch((err) => {});
        },

        unreadNotification() {
            this.$server
                .get("/api/notifications/unread")
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
