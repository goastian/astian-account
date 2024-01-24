<template>
    <div class="border-bottom clearfix">
        <strong class="float-start text-capitalize">Notificaciones</strong>
        <a href="#" class="btn btn-link float-end" @click="clean">
            Marcar como leidas
        </a>
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
                <span class="float-start">Recibida {{ item.recibido }}</span>
                <span class="ms-auto float-end mx-2"
                    >{{ item.leido ? "Leida" : "" }} {{ item.leido }}</span
                >
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
                    console.log(err);
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
                    console.log(err);
                });
        },

        mark_as_read(link) {
            this.$server
                .post(link)
                .then((res) => {
                    this.unread();
                })
                .catch((err) => {
                    console.log(err);
                });
        },

        remove(link) {
            this.$server
                .delete(link)
                .then((res) => {
                    this.unread();
                })
                .catch((err) => {
                    console.log(err);
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
