<template>
    <div>
        <el-menu mode="horizontal">
            <el-menu-item>
                <span>
                    <slot name="icon"></slot>
                </span>
            </el-menu-item>
            <el-menu-item @click="goHome">
                <el-icon><House /></el-icon> Home
            </el-menu-item>
            <el-menu-item
                v-for="(app, index) in apps"
                :key="index"
                @click="goPage(app.url)"
            >
                <el-icon><i v-html="app.icon"></i> </el-icon> {{ app.name }}
            </el-menu-item>
        </el-menu>
    </div>
</template>
<script>
export default {
    data() {
        return {
            apps: [],
        };
    },

    mounted() {
        this.getApps();
        this.listenEnvents();
    },

    methods: {
        async getApps() {
            try {
                const res = await this.$server.get("/api/settings/apps");

                if (res.status == 200) {
                    this.apps = res.data.data;
                }
            } catch (error) {}
        },

        goHome() {
            this.$router.push({ name: "home" });
        },

        goPage(uri) {
            window.location.href = uri;
        },

        listenEnvents() {
            this.$echo
                .channel(this.$channels.ch_0())
                .listen("AppCreated", (e) => {
                    this.getApps();
                });

            this.$echo
                .channel(this.$channels.ch_0())
                .listen("AppUpdated", (e) => {
                    this.getApps();
                });

            this.$echo
                .channel(this.$channels.ch_0())
                .listen("AppDeleted", (e) => {
                    this.getApps();
                });
        },
    },
};
</script>
<style lang=""></style>
