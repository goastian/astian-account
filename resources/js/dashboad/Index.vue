<template>
    <div class="row p-0 m-0">
        <v-nav class="nav-top" @expand="taggleLefbar" :status="toggle_left_bar"></v-nav>
        <div class="left" v-show="!toggle_left_bar">
            <v-left-bar @selected-menu="taggleLefbar"></v-left-bar>
        </div>
        <div
            :class="{
                'pt-2 ': true,
                'body-expand': toggle_left_bar,
                body: !toggle_left_bar,
            }"
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
            toggle_left_bar: false,
        };
    },

    methods: {
        taggleLefbar(event) {
            this.toggle_left_bar = event;
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
    background-color: var(--nav-left-bg);

    @media (min-width: 240px) {
        width: 100%;
    }

    @media (min-width: 850px) {
        width: 15%;
        min-height: 100vh;
    }
}

.route {
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
