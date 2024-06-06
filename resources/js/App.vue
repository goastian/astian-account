<template>
    <div class="body">
        <v-leftbar class="left"></v-leftbar>
        <div class="content">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import VHome from "./dashboad/Index.vue";
import VLeftbar from "./dashboad/Leftbar.vue";

export default {
    components: {
        VHome,
        VLeftbar,
    },
};
</script>

<style lang="scss" scoped>
.body {
    display: flex;
    .left {
        flex: 1 1 auto;
    }
    .content {
        flex: 1 1 auto;
        padding: 0.1em;
    }
}
</style>
