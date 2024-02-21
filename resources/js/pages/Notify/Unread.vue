<template>
    <div class="border-bottom clearfix">
        <strong class="float-start text-capitalize text-color">Notifications</strong>
        <a href="#" class="btn btn-link float-end" @click="clean">
            Mark all as read
        </a>
    </div>
    <div class="notification-read">
        <div
            :class="['card', item.leido ? 'border-success' : 'border-danger']"
            v-for="(item, index) in notifications"
            :key="index"
        >
            <div class="card-head text-color py-0 my-0 text-center">
                {{ item.titulo }}
                <a
                    class="btn btn-link text-danger float-end"
                    href="#"
                    @click="remove(item.links.destroy)"
                    ><i class="bi bi-x-circle-fill h2"></i
                ></a>
            </div>
            <div class="card-body py-0 my-0 text-color text-center text-sm">
                <span>{{ item.mensaje }}</span>
                <a
                    class="btn btn-link"
                    :href="item.recurso"
                    target="_blank"
                    @click="mark_as_read(item.links.read)"
                    >Read more ...</a
                >
            </div>
            <div class="card-footer text-color">
                <span class="float-start">received {{ item.recibido }}</span>
                <span class="ms-auto float-end mx-2"
                    >{{ item.leido ? "Leida" : "" }} {{ item.leido }}</span
                >
            </div>
        </div>
    </div>
    <v-pagination
        v-show="pages.total > pages.per_page"
        :pages="pages"
        @send-current-page="changePage"
    ></v-pagination>
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
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response);
                    }
                });
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
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response);
                    }
                });
        },

        mark_as_read(link) {
            this.$server
                .post(link)
                .then((res) => {
                    this.unread();
                })
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response);
                    }
                });
        },

        remove(link) {
            this.$server
                .delete(link)
                .then((res) => {
                    this.unread();
                })
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response);
                    }
                });
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
