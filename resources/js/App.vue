<template>
    <!--Start the application-->
    <div>
        <div v-show="authenticated">
            <v-home></v-home>
        </div>

        <div class="guest py-1" v-show="!authenticated">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import VHome from "./dashboad/Index.vue";

export default {
    components: {
        VHome,
    },

    computed: {
        authenticated() {
            return this.$route.meta.auth || false;
        },
    },
};
</script>
