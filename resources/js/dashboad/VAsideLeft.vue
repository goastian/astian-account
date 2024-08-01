<template>
    <el-menu :collapse="collapse">
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
                <el-icon><BellFilled /></el-icon>
                <span> Notification </span>
            </template>
            <el-menu-item @click="goPushNotifications">Send</el-menu-item>
            <el-menu-item @click="goAllNotifications">All</el-menu-item>
            <el-menu-item @click="goUnreadNotifications">Unread</el-menu-item>
        </el-sub-menu>
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
    },
};
</script> 
