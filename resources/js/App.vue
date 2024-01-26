<template>
    <div :class="[expand ? 'expand-body' : 'body']">
        <div v-show="!expand" class="aside">
            <v-left-bar @select-menu="Expand"></v-left-bar>
        </div>
        <div class="section">
            <div class="navbar py-0">
                <v-nav @expand="Expand" :status="expand"></v-nav>
            </div>
            <div class="content p-2">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>
<script>
import VNav from "./Pages/Dashboad/Navbar.vue";
import VLeftBar from "./Pages/Dashboad/Leftbar.vue";

export default {
    components: {
        VNav,
        VLeftBar,
    },

    data() {
        return {
            user: {},
            expand: false,
        };
    },

    mounted() {
        window.addEventListener("resize", this.screenIsChanging);
        this.screenIsChanging();
    },
    created() {
        this.authenticated();
    },

    methods: {
        Expand(event) {
            this.expand = event; 
        },

        screenIsChanging() {
            this.expand = window.innerWidth < 940;
        },

        async authenticated() {
            try {
                const res = await this.$server.get("/api/gateway/user");
                this.user = res.data;
                window.$auth = res.data;
            } catch (err) {
                console.log(err);
            }
        },

        listener() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("UpdateEmployeeEvent", (e) => {
                    this.authenticated();
                });
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../scss/__colors.scss";

.body {
    background-color: $blue;
    @media (min-width: 240px) {
        grid-template-columns: 0% 100%;
    }

    @media (min-width: 940px) {
        display: grid;
        grid-template-columns: 20% 80%;
    }
}

.body .section > div {
    display: block;
    padding: 0;
}

.aside {
    height: 100vh;
    overflow-y: scroll;
}

.content {
    padding: 0;
    margin: 0;
    height: 100vh;
    overflow-y: scroll;
    background-color: #ffff;
}

//expand body
.expand-body {
}
</style>
