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
