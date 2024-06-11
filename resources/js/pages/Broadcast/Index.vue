<template>
    <v-register @success="getChannels"></v-register>

    <v-table>
        <template v-slot:title> List of channels </template>
        <template v-slot:head>
            <th>channel</th>
            <th>description</th>
            <th>created</th>
        </template>
        <template v-slot:body>
            <tr v-for="(item, index) in channels" :key="index">
                <td>{{ item.channel }}</td>
                <td>{{ item.description }}</td>
                <td>{{ item.created }}</td>
                <td>
                    <v-remove
                        :item="item"
                        @success="getChannels"
                        @errors="showMessage"
                    ></v-remove>
                </td>
            </tr>
        </template>
    </v-table>

    <v-pagination
        v-show="pages.total > pages.per_page"
        :pages="pages"
        @send-current-page="updateList"
    ></v-pagination>

    <v-message :id="message_show">
        <template v-slot:body>
            {{ message }}
        </template>
    </v-message>
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
            channels: {},
            pages: {},
            search: {
                page: 1,
                per_page: 30,
            },
            message: null,
            message_show: null,
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

        showMessage(event) {
            this.message_show = Math.floor(Math.random() * 10000);
            this.message = event.data.message;
        },

        async getChannels() {
            try {
                const res = await this.$server.get("/api/broadcasts", {
                    params: this.search,
                });

                if (res.status) {
                    this.channels = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (e) {
                if (e.response && e.response.status == 403) {
                    this.message = e.response.data.message;
                }
            }
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
th {
    text-align: start;
    &::first-letter {
        text-transform: uppercase;
    }
}

tr {
    td {
        text-align: start;
        &::first-letter {
            text-transform: uppercase;
        }
        &:nth-child(2) {
            min-width: 150px;
        }
    }
}
</style>
