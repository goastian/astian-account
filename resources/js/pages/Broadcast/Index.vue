<template>
    <v-register @success="getChannels"></v-register>

    <v-table :items="items">
        <template v-slot:body>
            <tr v-for="(item, index) in channels" :key="index">
                <td class="fw-light"> {{ item.channel }} </td>
                <td class="fw-light"> {{ item.description }} </td>
                <td class="fw-light"> {{ item.created }} </td>
                <td>
                    <v-remove :item="item" @success="getChannels"></v-remove>
                </td>
            </tr>
        </template>
    </v-table>

    <v-pagination
        v-show="pages.total > pages.per_page"
        :pages="pages"
        @send-current-page="updateList"
    ></v-pagination>

    <v-message :message="message" @close="close"></v-message>
</template>
<script>
import VRegister from "./Register.vue";
import VRemove from "./Remove.vue";

export default {
    components: {
        VRegister,
        VRemove,
    },

    data() {
        return {
            items: ["Channel", "description", "registered"],
            channels: {},
            pages: {},
            search: {
                page: 1,
            },
            message: null,
        };
    },

    mounted() {
        this.getChannels();
        this.listenChannels();
    },

    methods: {
        updateList(page) {
            this.search.page = page;
            this.getChannels();
        },

        close() {
            this.message = null;
        },

        getChannels() {
            this.$server
                .get("/api/broadcasts", {
                    params: this.search,
                })
                .then((res) => {
                    this.channels = res.data.data;
                    this.pages = res.data.meta.pagination;
                })
                .catch((e) => {
                    if (e.response && e.response.status == 403) {
                        this.message = e.response.data.message;
                    }
                });
        },

        listenChannels() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("StoreBroadcastEvent", (e) => {
                    this.updateList();
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyBroadcastEvent", (e) => {
                    this.updateList();
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.col {
    flex: 0 0 auto;
    width: 90%;

    @media (min-width: 240px) {
        width: 98%;
        margin: 1% 0;
        padding: 0;
    }

    @media (min-width: 850px) {
        width: 48%;
        margin: 1%;
    }
}
</style>
