<template>
    <div v-if="user.id">
        <router-view></router-view>
    </div>
    <div v-if="!user.id">
        <div
            id="loading-screen"
            class="fixed inset-0 flex items-center justify-center bg-white z-50"
        >
            <div class="text-center">
                <div
                    class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"
                ></div>
                <p class="text-lg font-medium text-gray-700 animate-pulse">
                    Loading ...
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { computed, nextTick } from "vue";
export default {
    data() {
        return {
            app_name: "",
            drawer: true,
            user: {},
            errors: {},
        };
    },

    provide() {
        return {
            $user: computed(() => this.user),
            $app_name: computed(() => this.app_name),
        };
    },

    mounted() {
        this.authenticated();
        this.appData();
    },

    methods: {
        async authenticated() {
            try {
                const res = await this.$server.get("/api/gateway/user");
                if (res.status === 200) {
                    this.user = res.data;
                }
            } catch (e) {}
        },

        async appData() {
            await nextTick();
            const app = document.querySelector("#app");
            this.app_name = app.dataset.appName;
        },
    },
};
</script>
