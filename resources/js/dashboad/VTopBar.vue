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
            <el-menu-item @click="goCloud">
                <el-icon><Cloudy /></el-icon> Cloud
            </el-menu-item>
            <el-menu-item @click="goCalendar">
                <el-icon><Calendar /></el-icon>Calendar
            </el-menu-item>
            <el-menu-item @click="goContacts">
                <el-icon><Notebook /></el-icon> Contacts
            </el-menu-item>
            <el-menu-item @click="goAstiango">
                <el-icon><Search /></el-icon> AstianGO
            </el-menu-item>
            <el-menu-item @click="goMidori"> Midori </el-menu-item>
        </el-menu>
    </div>
</template>
<script>
export default {
    data() {
        return {};
    },

    methods: {
        goHome() {
            this.$router.push({ name: "home" });
        },
        goCloud() {
            window.location.href = process.env.MIX_MENU_CLOUD;
        },
        goCalendar() {
            window.location.href = process.env.MIX_MENU_CALENDAR;
        },
        goContacts() {
            window.location.href = process.env.MIX_MENU_NOTES;
        },
        goAstiango() {
            window.location.href = "https://astiango.co";
        },

        goMidori() {
            window.location.href =
                "https://astian.org/midori-browser/download/";
        },
    },
};
</script>
<style lang=""></style>
