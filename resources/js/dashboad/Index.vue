<template>
    <div class="row p-0 m-0">
        <v-nav @expand="taggleLefbar" :status="toggle_lef_bar"></v-nav>
        <div class="left" v-show="!toggle_lef_bar">
            <v-left-bar @selected-menu="taggleLefbar"></v-left-bar>
        </div>
        <div
            :class="['pt-2 content', [toggle_lef_bar ? 'body-expand' : 'body']]"
        >
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import VNav from "./Navbar.vue";
import VLeftBar from "./Leftbar.vue";

export default {
    components: {
        VNav,
        VLeftBar,
    },

    data() {
        return {
            toggle_lef_bar: false,
        };
    },

    methods: {
        taggleLefbar(event) {
            this.toggle_lef_bar = event;
        },
    },
};
</script>

<style scoped lang="scss">
.left {
    flex: 0 0 auto;
    overflow-y: scroll;
    padding: 0;
    margin: 0;

    @media (min-width: 240px) {
        width: 100%;
    }

    @media (min-width: 850px) {
        width: 15%;
        height: 100vh;
    }
}

.content {
    overflow-y: auto;
}

.body {
    @media (min-width: 240px) {
        width: 100%;
    }

    @media (min-width: 800px) {
        width: 84%;
    }
}

.body-expand { 
    width: 100%;
    min-height: 100vh;
}
</style>
