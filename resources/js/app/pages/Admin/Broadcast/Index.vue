<template>
    <v-filter :params="params" @change="searching" />
    <q-toolbar class="q-ma-sm">
        <q-toolbar-title> List of channels </q-toolbar-title>

        <v-create @created="getBroadcasting"></v-create>
    </q-toolbar>

    <div class="row q-col-gutter-md q-ma-sm">
        <div
            class="col-xs-12 col-sm-6 col-md-4"
            v-for="channel in channels"
            :key="channel.id"
        >
            <q-card bordered flat>
                <q-card-section class="q-pb-none">
                    <div class="text-h6">{{ channel.name }}</div>
                    <div class="text-subtitle2 text-grey">
                        {{ channel.slug }}
                    </div>
                </q-card-section>

                <q-card-section>
                    <div class="q-mb-sm">
                        <strong>Description:</strong>
                        {{ channel.description || "—" }}
                    </div>
                    <div>
                        <strong>System:</strong>
                        <q-chip
                            :color="channel.system ? 'green' : 'red'"
                            text-color="white"
                            dense
                        >
                            {{ channel.system ? "Yes" : "No" }}
                        </q-chip>
                    </div>
                </q-card-section>

                <q-card-actions align="right">
                    <v-destroy
                        v-if="!channel.system"
                        :item="channel"
                        @deleted="getBroadcasting"
                    />
                </q-card-actions>
            </q-card>
        </div>
    </div>

    <div class="row justify-center q-mt-md">
        <q-pagination
            v-model="search.page"
            color="grey-8"
            :max="pages.total_pages"
            size="sm"
        />
    </div>
</template>
<script>
import VCreate from "./Create.vue";
import VDestroy from "./Destroy.vue";
export default {
    components: {
        VCreate,
        VDestroy,
    },

    data() {
        return {
            params: [
                { key: "Name", value: "name" },
                { key: "Description", value: "description" },
                { key: "slug", value: "slug" },
                { key: "System", value: "system" },
            ],
            channels: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
        };
    },

    created() {
        this.getBroadcasting();
    },

    watch: {
        "search.page"(value) {
            this.getBroadcasting();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getBroadcasting();
            }
        },
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getBroadcasting(event);
        },
        /**
         * Get the all channels
         */
        async getBroadcasting(param = null) {
            //setting searching params
            var params = {};
            Object.assign(params, this.search);
            Object.assign(params, param);

            try {
                const res = await this.$server.get("/api/admin/broadcasts", {
                    params: params,
                });

                if (res.status == 200 && res.data.data.length) {
                    const values = res.data.data;
                    const meta = res.data.meta;

                    this.channels = values;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                }
            } catch (e) {}
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("CreatedBroadcasting", (e) => {
                    this.getBroadcasting();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyedBroadcasting", (e) => {
                    this.getBroadcasting();
                });
        },
    },
};
</script>
