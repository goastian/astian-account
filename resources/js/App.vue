<template>

    <div v-show="authenticated">
        <v-home ></v-home>
    </div>

    <div class="guest py-1" v-show="!authenticated">
        <router-view></router-view>
    </div>

    <v-footer></v-footer>
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