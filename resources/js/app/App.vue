<template>
    <div>
        <q-layout view="lHh Lpr lFf">
            <div
                v-if="loader"
                class="fixed inset-0 z-max flex items-center justify-center bg-white"
            >
                <q-spinner-dots color="primary" size="64px" />
                <div class="q-ml-md text-primary text-subtitle1">
                    loading ...
                </div>
            </div>
            <router-view v-else />
        </q-layout>
    </div>
</template>

<script>
import { computed, nextTick } from "vue";

export default {
    data() {
        return {
            app_name: "",
            user: {},
            loader: true,
        };
    },

    provide() {
        return {
            $user: computed(() => this.user),
            $app_name: computed(() => this.app_name),
        };
    },

    mounted() {
        this.appData();
        this.authenticated();
    },

    methods: {
        async authenticated() {
            try {
                const res = await this.$server.get("/api/gateway/user");
                if (res.status === 200) {
                    this.user = res.data;
                }
            } catch (e) {
            } finally {
                this.loader = false;
            }
        },

        async appData() {
            await nextTick();
            const app = document.querySelector("#app");
            this.app_name = app.dataset.appName;
        },
    },
};
</script>
