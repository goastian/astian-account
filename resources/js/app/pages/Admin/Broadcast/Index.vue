<template>
    <v-sheet class="px-3">
        <v-data-table
            :items-per-page="search.per_page"
            :headers="headers"
            :items="channels"
        >
            <template v-slot:top>
                <div class="flex mx-3 justify-between align-center">
                    <h1 class="fw-bold">List of channels</h1>
                    <v-create @created="getBroadcasting"></v-create>
                </div>
                <v-filter :params="params" @change="searching"></v-filter>
            </template>
            <template #item.system="{ item }">
                <v-chip>
                    {{ item.system ? "Yes" : "No" }}
                </v-chip>
            </template>
            <template #item.actions="{ item }">
                <v-destroy :item="item" @deleted="getBroadcasting"></v-destroy>
            </template>
            <template v-slot:bottom>
                <v-pagination
                    v-model="search.page"
                    :length="pages.total_pages"
                    :total-visible="7"
                ></v-pagination>
            </template>
        </v-data-table>
    </v-sheet>
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
            headers: [
                { title: "Name", value: "name", align: "start" },
                { title: "Description", value: "description", align: "start" },
                { title: "Slug", value: "slug", align: "start" },
                { title: "System", value: "system", align: "start" },
                { title: "Actions", value: "actions", align: "center" },
            ],
            params: ["name", "description", "slug", "system"],
            channels: [],
            pages: {},
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
