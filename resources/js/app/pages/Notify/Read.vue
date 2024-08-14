<template>
    <el-card>
        <template #header>
            <div class="head">
                <div class="row">
                    <div class="col">
                        <el-badge
                            :value="notifications.length"
                            class="item"
                            type="danger"
                        >
                            <el-button type="primary">Notifications</el-button>
                        </el-badge>
                    </div>
                    <div class="col">
                        <el-button @click="clean" type="danger">
                            Clean
                        </el-button>
                    </div>
                </div>
            </div>
        </template>
        <el-card
            v-for="(item, index) in notifications"
            :key="index"
            style="margin-bottom: 0.5em"
        >
            <div class="notification">
                <div class="head">
                    <el-text class="mx-1" tag="b"> {{ item.subject }} </el-text>
                </div>
                <div class="content">
                    <el-text class="mx-1"> {{ item.message }} </el-text>
                    <el-button type="success" link @click="open_link(item)">
                        read more ...
                    </el-button>
                </div>
                <div class="foot">
                    <div class="row">
                        <div class="col">
                            <el-popover
                                placement="top-start"
                                title="Notification"
                                trigger="hover"
                                content="Maks as read"
                            >
                                <template #reference>
                                    <el-button
                                        type="success"
                                        @click="mark_as_read(item.links.read)"
                                        class="m-2"
                                        >{{ item.created }}</el-button
                                    >
                                </template>
                            </el-popover>
                        </div>
                        <div class="col">
                            <el-text class="mx-1">
                                Read {{ item.read }}
                            </el-text>
                        </div>
                    </div>
                </div>
            </div>
        </el-card>
    </el-card>
</template>
<script>
export default {
    data() {
        return {
            notifications: [],
            pages: {},
            search: {
                page: 1,
                params: 8,
            },
        };
    },
    created() {
        this.read();
        this.listenEvents();
    },

    methods: {
        changePage(event) {
            this.search.page = event;
            this.read();
        },

        clean() {
            this.$server
                .delete("api/notifications/clean", {
                    params: this.search,
                })
                .then((res) => {
                    this.read();
                })
                .catch((err) => {});
        },

        open_link(item) {
            this.$server
                .post(item.links.read)
                .then((res) => {
                    window.location.href = item.resource;
                    this.read();
                })
                .catch((err) => {});
        },

        read() {
            this.$server
                .get("api/notifications", {
                    params: this.search,
                })
                .then((res) => {
                    this.notifications = res.data.data;
                    this.pages = res.data.meta.pagination;
                })
                .catch((err) => {});
        },

        mark_as_read(link) {
            this.$server
                .post(link)
                .then((res) => {
                    this.read();
                })
                .catch((err) => {});
        },

        remove(link) {
            this.$server
                .delete(link)
                .then((res) => {
                    this.read();
                })
                .catch((err) => {});
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("PushNotificationEvent", (e) => {
                    this.read();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("ReadNotificationEvent", (e) => {
                    this.read();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyNotificationEvent", (e) => {
                    this.read();
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.head {
    .row {
        display: flex;
        flex-wrap: wrap;

        .col {
            flex: calc(95% / 2);

            &:nth-child(2) {
                text-align: end;
            }
        }
    }
}

.notification {
    .head {
        margin-bottom: 0.2em;
    }

    .content {
        margin-bottom: 0.2em;
    }

    .foot {
        .row {
            display: flex;
            flex-wrap: wrap;
            .col {
                flex: 1 1 calc(100% / 2);

                &:nth-child(2) {
                    text-align: end;
                }
            }
        }
    }
}
</style>
