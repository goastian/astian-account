<template>
    <v-user-layout>
        <q-page class="q-pa-md">
            <q-card flat bordered class="q-pa-md">
                <q-tabs
                    v-model="tab"
                    dense
                    class="text-primary"
                    active-color="primary"
                    indicator-color="primary"
                    align="left"
                    narrow-indicator
                >
                    <q-tab name="all" label="Read" />
                    <q-tab name="unread">
                        <q-badge color="red" floating transparent>
                            {{ unread_notification.length }}
                        </q-badge>
                        Unread
                    </q-tab>
                </q-tabs>

                <q-separator />

                <q-tab-panels v-model="tab" animated class="q-mt-md">
                    <!-- All Notifications -->
                    <q-tab-panel name="all">
                        <q-list separator>
                            <template v-if="notifications.length">
                                <q-item
                                    v-for="notification in notifications"
                                    :key="notification.id"
                                    clickable
                                    @click="markAsRead(notification)"
                                    class="q-mb-sm rounded-borders"
                                >
                                    <q-item-section avatar top>
                                        <q-icon
                                            name="notifications"
                                            color="primary"
                                        />
                                    </q-item-section>

                                    <q-item-section>
                                        <q-item-label
                                            class="text-subtitle1 text-bold"
                                        >
                                            {{ notification.title }}
                                        </q-item-label>
                                        <q-item-label caption>
                                            {{ notification.message }}
                                        </q-item-label>
                                    </q-item-section>

                                    <q-item-section side>
                                        <q-item-label caption>
                                            {{ notification.created }}
                                        </q-item-label>
                                    </q-item-section>
                                </q-item>
                            </template>

                            <template v-else>
                                <div class="text-center q-mt-md">
                                    <q-icon
                                        name="inbox"
                                        size="32px"
                                        class="q-mb-sm"
                                    />
                                    <div>There are no notifications</div>
                                </div>
                            </template>
                        </q-list>
                    </q-tab-panel>

                    <!-- Unread Notifications -->
                    <q-tab-panel name="unread">
                        <q-list separator>
                            <template v-if="unread_notification.length">
                                <q-item
                                    v-for="notification in unread_notification"
                                    :key="notification.id"
                                    clickable
                                    @click="markAsRead(notification)"
                                    class="q-mb-sm rounded-borders"
                                >
                                    <q-item-section avatar top>
                                        <q-icon
                                            name="mark_email_unread"
                                            color="red"
                                        />
                                    </q-item-section>

                                    <q-item-section>
                                        <q-item-label
                                            class="text-subtitle1 text-bold"
                                        >
                                            {{ notification.title }}
                                        </q-item-label>
                                        <q-item-label caption>
                                            {{ notification.message }}
                                        </q-item-label>
                                    </q-item-section>

                                    <q-item-section side>
                                        <q-item-label caption>
                                            {{ notification.created }}
                                        </q-item-label>
                                    </q-item-section>
                                </q-item>
                            </template>

                            <template v-else>
                                <div class="text-center q-mt-md">
                                    <q-icon
                                        name="mark_email_read"
                                        size="32px"
                                        class="q-mb-sm"
                                    />
                                    <div>No unread notifications</div>
                                </div>
                            </template>
                        </q-list>
                    </q-tab-panel>
                </q-tab-panels>
            </q-card>
        </q-page>
    </v-user-layout>
</template>

<script>
export default {
    data() {
        return {
            notifications: [],
            unread_notification: [],
            tab: "unread",
        };
    },

    mounted() {
        this.getNotifications();
        this.getUnreadNotifications();
    },

    methods: {
        async getNotifications() {
            try {
                const res = await this.$server.get(
                    this.$page.props.route["all"]
                );
                if (res.status === 200) {
                    this.notifications = res.data.data;
                }
            } catch (error) {}
        },

        openLink(url) {
            if (url) {
                window.open(url, "_blank");
            }
        },

        async markAsRead(notification) {
            try {
                const res = await this.$server.post(
                    notification.links.mark_as_read
                );

                if (res.status == 201) {
                    this.getUnreadNotifications();
                    this.getNotifications();
                    this.openLink(notification.link);
                }
            } catch (error) {}
        },

        async getUnreadNotifications() {
            try {
                const res = await this.$server.get(
                    this.$page.props.route["unread"]
                );
                if (res.status === 200) {
                    this.unread_notification = res.data.data;
                }
            } catch (error) {}
        },
    },
};
</script>
<style scoped>
.q-item {
    transition: background-color 0.2s ease;
}
.q-item:hover {
    color: var(--secondary);
}
</style>
