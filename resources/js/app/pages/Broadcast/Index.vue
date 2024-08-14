<template>
    <div class="channels">
        <div class="head">
            <div class="row">
                <div class="col">
                    <p>
                        List of Channels
                        <el-input-number
                            v-model="search.per_page"
                            :min="1"
                            :max="50"
                        />
                    </p>
                </div>
                <div class="col">
                    <v-register></v-register>
                </div>
            </div>
        </div>
        <div class="table">
            <div class="table" style="margin-bottom: 1em">
                <el-table :data="channels" :lazy="true">
                    <el-table-column
                        prop="channel"
                        label="Channel"
                        width="200"
                    />
                    <el-table-column
                        prop="description"
                        label="description"
                        width="300"
                    />
                    <el-table-column
                        prop="created"
                        label="created"
                        width="200"
                    />

                    <el-table-column label="Operations" min-width="200">
                        <template #default="scope">
                            <v-remove :item="scope.row"></v-remove>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
        <el-pagination
            v-if="pages.total > 0"
            background
            layout="prev, pager, next"
            @change="changePage"
            :page-count="pages.total_pages"
            :total="pages.total"
        />
    </div>
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
            channels: [],
            pages: {},
            search: {
                page: 1,
                per_page: 30,
            },
        };
    },

    mounted() {
        this.getChannels();
        this.listenChannels();
    },

    watch: {
        "search.page"(value) {
            this.getChannels();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getChannels();
            }
        },
    },

    methods: {
        updateList(page) {
            this.search.page = page;
            this.getChannels();
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
.channels {
    .head {
        .row {
            display: flex;
            flex-wrap: wrap;

            .col {
                flex: 1 1 calc(100% / 2);
                &:nth-child(2) {
                    text-align: center;
                }
            }
        }
    }
}
</style>
