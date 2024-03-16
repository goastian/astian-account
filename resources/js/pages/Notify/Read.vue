<template>
    <div>
        <p class="text-capitalize text-color fw-bold border-bottom">
            Notifications

            <a href="#" class="btn btn-link float-end p-0 m-0" @click="clean">
                Remove all
            </a>
        </p>

        <ul class="read">
            <li
                class="text-color"
                v-for="(item, index) in notifications"
                :key="index"
            >
                <strong class="p-0 m-0 border-bottom">{{
                    item.subject
                }}</strong>

                <i
                    class="bi bi-x-circle-fill float-end"
                    style="cursor: pointer"
                    @click="remove(item.links.destroy)"
                ></i>

                <p class="m-0 text-sm">
                    {{ item.message }}
                    <a
                        class="btn btn-link text-sm"
                        :href="item.resource"
                        target="_blank"
                        @click="mark_as_read(item.links.read)"
                    >
                        Read more ...
                    </a>
                </p>
                <span class="text-primary text-sm date"
                    >Received {{ item.created }}</span
                >
                <span class="text-success text-sm date"
                    >{{ item.read ? "Read" : "" }} {{ item.read }}</span
                >
            </li>
        </ul>

        <v-pagination
            v-show="pages.total > pages.per_page"
            :pages="pages"
            @send-current-page="changePage"
        ></v-pagination>
    </div>
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
.read {
    list-style: none;
    padding: 0 2%;
}

.read li {
    margin-bottom: 2%;
}

.date:last-child {
    display: block;
    @media (min-width: 800px) {
        float: inline-end;
    }
}
</style>
