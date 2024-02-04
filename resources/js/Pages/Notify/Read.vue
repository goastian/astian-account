<template>
    <div class="border-bottom clearfix">
        <strong class="float-start text-capitalize">Notificaciones</strong>
        <a href="#" class="btn btn-link float-end" @click="clean">
            remover todas</a
        >
    </div>
    <div class="notification-read">
        <div
            :class="['card', item.leido ? 'border-success' : 'border-danger']"
            v-for="(item, index) in notifications"
            :key="index"
        >
            <div class="card-head">
                {{ item.titulo }}
                <a
                    class="btn btn-link text-danger float-end"
                    href="#"
                    @click="remove(item.links.destroy)"
                    ><i class="bi bi-x-circle-fill h2"></i
                ></a>
            </div>
            <div class="card-body">
                <p>{{ item.mensaje }}</p>
                <a
                    class="btn btn-link"
                    :href="item.recurso"
                    target="_blank"
                    @click="mark_as_read(item.links.read)"
                    >Leer ...</a
                >
            </div>
            <div class="card-footer">
                <span>Recibida {{ item.recibido }}</span>
                <span>{{ item.leido ? "Leida" : "" }} {{ item.leido }}</span>
            </div>
        </div>
    </div>
    <v-pagination :pages="pages" @send-current-page="changePage"></v-pagination>
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
