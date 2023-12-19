<template>
    <v-register @broadcast-was-created="getChannels"></v-register>

    <v-table class="text-sm" :items="items" style="width: 70%; margin: auto">
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
                    <v-remove
                        :item="item"
                        @broadcast-was-remove="getChannels()"
                    ></v-remove>
                </td>
            </tr>
        </template>
    </v-table>

    <v-pagination :pages="pages" @send-current-page="updateList"></v-pagination>
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
        };
    },

    created() {
        this.getChannels();
    },

    methods: {
        updateList(page) {
            this.search.page = page;
            this.getChannels();
        },

        getChannels() {
            window.axios
                .get("/api/broadcasts", {
                    params: this.search,
                })
                .then((res) => {
                    this.channels = res.data.data;
                    this.pages = res.data.meta.pagination;
                })
                .catch((e) => {
                    console.error(e.response);
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
