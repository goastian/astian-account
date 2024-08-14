<template>
    <div class="common-layout">
        <el-container>
            <el-aside v-if="!show_side_bar">
                <v-aside-left
                    :collapse="collapse"
                    @clicked="hideMenu"
                ></v-aside-left>
            </el-aside>
            <el-container>
                <el-header>
                    <v-top-bar>
                        <template v-slot:icon>
                            <el-icon
                                v-if="show_side_bar"
                                @click="showHiddenMenu"
                                ><Menu
                            /></el-icon>
                            <el-icon
                                v-if="!show_side_bar"
                                size="25"
                                @click="collapseMenu"
                            >
                                <Menu />
                            </el-icon>
                        </template> </v-top-bar
                ></el-header>
                <el-main>
                    <el-breadcrumb separator="/" style="margin-bottom: 1em">
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
                </el-main>
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
            show_side_bar: false,
            aside_width: 30,
        };
    },

    mounted() {
        this.screenIsChanging();
    },

    methods: {
        collapseMenu() {
            this.collapse = !this.collapse;
            this.screenIsChanging();
        },

        showHiddenMenu() {
            this.collapse = false;
            this.show_side_bar = !this.show_side_bar;
        },

        screenIsChanging() {
            this.show_side_bar = window.innerWidth < 940;
        },

        hideMenu() {
            if (window.innerWidth < 940) {
                this.show_side_bar = true;
            }
        },
    },
};
</script>

<style lang="scss">
.el-container {
    .el-aside {
        width: auto !important;
        @media (max-width: 940px) {
            position: absolute;
            left: 0;
            right: 0;
            z-index: 999;
        }
    }
    .el-container {
        .el-main {
            min-height: 100vh;
        }
    }
}
</style>
