<template>
    <div class="border-bottom text-color">
        <strong class="float-start text-capitalize">Notifications</strong>
        <a href="#" class="btn btn-link float-end" @click="clean">
            Remove all</a
        >
    </div>
    <div class="notification-read">
        <div
            :class="['card', item.leido ? 'border-success' : 'border-danger']"
            v-for="(item, index) in notifications"
            :key="index"
        >
            <div class="card-head text-color">
                {{ item.titulo }}
                <a
                    class="btn btn-link text-danger float-end"
                    href="#"
                    @click="remove(item.links.destroy)"
                    ><i class="bi bi-x-circle-fill h2"></i
                ></a>
            </div>
            <div class="card-body py-0 text-color text-center text-sm">
                <span>{{ item.mensaje }}</span>
                <a
                    class="btn btn-link"
                    :href="item.recurso"
                    target="_blank"
                    @click="mark_as_read(item.links.read)"
                    >Read more ...</a
                >
            </div>
            <div class="card-footer my-0 py-0 text-color">
                <span>Recibida {{ item.recibido }}</span>
                <span>{{ item.leido ? "Leida" : "" }} {{ item.leido }}</span>
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
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response);
                    }
                });
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
                    this.read();
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
                    this.read();
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
.notification-read .card {
    width: 100% !important;
    text-align: center;
    margin: 2% auto;
    padding: 0% 1%;
}

.notification-read .card-footer {
    padding: 0% 0;
}

.notification-read .card-footer span {
    display: block;
    margin-top: 2%;

    @media (min-width: 800px) {
        display: inline;
    }
}

.notification-read .card-footer span:nth-child(1) {
    @media (min-width: 800px) {
        float: left;
    }
}

.notification-read .card-footer span:nth-child(2) {
    @media (min-width: 800px) {
        float: right;
    }
}
</style>
