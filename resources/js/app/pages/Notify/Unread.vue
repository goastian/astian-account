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
                            Mark as read
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
                    <el-text class="mx-1">
                        {{ item.message }}
                        <el-button type="success" link @click="open_link(item)">
                            read more ...
                        </el-button>
                    </el-text>
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
                                        @click="mark_as_read(item)"
                                        class="m-2"
                                        >{{ item.created }}</el-button
                                    >
                                </template>
                            </el-popover>
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
            notifications: {},
            pages: {},
            search: {
                page: 1,
                params: 8,
            },
        };
    },
    created() {
        this.unread();
        this.listenEvents();
    },

    methods: {
        changePage(event) {
            this.search.page = event;
            this.unread();
        },

        clean() {
            this.$server
                .post("api/notifications/mark_as_read")
                .then((res) => {
                    this.unread();
                })
                .catch((err) => {});
        },

        unread() {
            this.$server
                .get("api/notifications/unread", {
                    params: this.search,
                })
                .then((res) => {
                    this.notifications = res.data.data;
                    this.pages = res.data.meta.pagination;
                })
                .catch((err) => {});
        },

        open_link(item) {
            this.$server
                .post(item.links.read)
                .then((res) => {
                    window.location.href = item.resource;
                    this.unread();
                })
                .catch((err) => {});
        },

        mark_as_read(item) {
            this.$server
                .post(item.links.read)
                .then((res) => {
                    this.unread();
                })
                .catch((err) => {});
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("PushNotificationEvent", (e) => {
                    this.unread();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("ReadNotificationEvent", (e) => {
                    this.unread();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyNotificationEvent", (e) => {
                    this.unread();
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
                flex: 1 1 100%;
            }
        }
    }
}
</style>
