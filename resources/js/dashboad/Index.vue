<template>
    <div class="common-layout">
        <el-container>
            <el-header>
                <v-top-bar>
                    <template v-slot:icon>
                        <el-icon size="25" @click="collapseMenu">
                            <Expand />
                        </el-icon>
                    </template>
                </v-top-bar>
            </el-header>
            <el-container>
                <el-aside width="30" v-show="show_menu">
                    <v-aside-left :collapse="collapse"></v-aside-left>
                </el-aside>
                <el-container>
                    <div class="content">
                        <el-breadcrumb
                            separator="/"
                            style="margin-bottom: 1em"
                        >
                            <el-breadcrumb-item :to="{ name: 'home' }"
                                >Home</el-breadcrumb-item
                            >
                            <el-breadcrumb-item
                                v-for="(item, index) in currentRoute"
                                :key="index"
                                :to="$router.currentRoute.value.fullPath"
                            >
                                {{ item }}
                            </el-breadcrumb-item>
                        </el-breadcrumb>
                        <router-view></router-view>
                    </div>
                </el-container>
            </el-container>
        </el-container>
    </div>
</template>
<script>
import VAsideLeft from "./VAsideLeft.vue";
import VTopBar from "./VTopBar.vue"; 
export default {
    components: {
        VAsideLeft,
        VTopBar,
    },

    computed: {
        currentRoute() {
            var route = this.$router.currentRoute.value.fullPath;
            return route.split("/");
        },
    },

    data() {
        return {
            collapse: true,
            show_menu: true,
        };
    },
    methods: {
        collapseMenu() {
            this.collapse = !this.collapse;
            this.screenIsChanging();
        },

        screenIsChanging() {
            this.show_menu = window.innerWidth > 940;
        },
    },
};
</script>

<style scoped lang="scss">
.content {
    width: 100%;
    padding: 1em;
}
</style>
