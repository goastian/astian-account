<template>
    <v-register @success="getChannels"></v-register>

    <v-table :items="items">
        <template v-slot:body>
            <tr v-for="(item, index) in channels" :key="index">
                <td>
                    {{ item.canal }}
                </td>
                <td>
                    {{ item.descripcion }}
                </td>
                <td>
                    {{ item.registrado }}
                </td>
                <td>
                    <v-remove :item="item" @success="getChannels()"></v-remove>
                </td>
            </tr>
        </template>
    </v-table>

    <v-pagination :pages="pages" @send-current-page="updateList"></v-pagination>

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
            items: ["canal", "descripcion", "registrado"],
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
