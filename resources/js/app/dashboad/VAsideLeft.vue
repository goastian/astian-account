<template>
    <el-menu :collapse="collapse" style="min-height: 100vh">
        <el-sub-menu index="1">
            <template #title>
                <el-icon><UserFilled /></el-icon>
                <span>Dashboard</span>
            </template>
            <el-menu-item v-if="checkGroup('admin')" @click="goUsers"
                >Users</el-menu-item
            >
            <el-menu-item v-if="checkGroup('admin')" @click="goRoles"
                >Roles</el-menu-item
            >
            <el-menu-item v-if="checkGroup('admin')" @click="goBroadcasts"
                >Broadcast</el-menu-item
            >
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
                <span>Notifications</span>
            </template>
            <el-menu-item
                v-if="checkGroup('admin')"
                @click="goPushNotifications"
                >Send</el-menu-item
            >
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
            <span>Logout</span>
        </el-menu-item>
    </el-menu>
</template>
<script>
export default {
    emits: ["clicked"],

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
        };
    },

    mounted() {
        this.unreadNotification();
        this.notification();
        this.listenEvents();
    },

    methods: {
        goUsers() {
            this.$emit("clicked");
            this.$router.push({ name: "users" });
        },

        goRoles() {
            this.$emit("clicked");
            this.$router.push({ name: "scopes" });
        },

        goBroadcasts() {
            this.$emit("clicked");
            this.$router.push({ name: "channels" });
        },

        goSecurity() {
            this.$emit("clicked");
            this.$router.push({ name: "security" });
        },

        goClients() {
            this.$emit("clicked");
            this.$router.push({ name: "clients" });
        },

        goCredentials() {
            this.$emit("clicked");
            this.$router.push({ name: "personalTokens" });
        },
        goTokenGenerated() {
            this.$emit("clicked");
            this.$router.push({ name: "tokens" });
        },
        goPushNotifications() {
            this.$emit("clicked");
            this.$router.push({ name: "notify" });
        },
        goAllNotifications() {
            this.$emit("clicked");
            this.$router.push({ name: "notify.read" });
        },
        goUnreadNotifications() {
            this.$emit("clicked");
            this.$router.push({ name: "notify.unread" });
        },

        screenIsChanging() {
            this.expand = window.innerWidth < 940;
        },

        async logout() {
            try {
                const res = await this.$server.post("/api/gateway/logout");
                if (res.status == 200) {
                    window.location.href = "/login";
                }
            } catch (error) {}
        },

        async notification() {
            try {
                const res = await this.$server.get("/api/notifications");
                if (res.status == 200) {
                    this.notifications = res.data.data;
                }
            } catch (error) {}
        },

        async unreadNotification() {
            try {
                const res = await this.$server.get("/api/notifications/unread");
                if (res.status == 200) {
                    this.unread_notifications = res.data.data;
                }
            } catch (error) {}
        },

        async readNotification(link) {
            try {
                const res = this.$server.post(link);
                if (res.status == 200) {
                    this.notification();
                }
            } catch (error) {}
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

        checkGroup(group) {
            return this.$user.groups.some((g) => g.name === group);
        },
    },
};
</script>
