<template>
    <v-filter :params="params" @change="searching"></v-filter>
    <q-table
        flat
        bordered
        :rows="channels"
        :columns="headers"
        row-key="name"
        hide-bottom
        :rows-per-page-options="[search.per_page]"
        hide-pagination
    >
        <template v-slot:top>
            <h6 class="fw-bold">List of channels</h6>
            <q-space />
            <v-create @created="getBroadcasting"></v-create>
        </template>

        <template v-slot:body-cell-system="props">
            <q-td>
                {{ props.row.system ? "Yes" : "No" }}
            </q-td>
        </template>
        <template v-slot:body-cell-actions="props">
            <q-td>
                <v-destroy
                    v-if="!props.row.system"
                    :item="props.row"
                    @deleted="getBroadcasting"
                ></v-destroy>
            </q-td>
        </template>
    </q-table>

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
            headers: [
                { label: "Name", name: "name", field: "name", align: "left" },
                {
                    label: "Description",
                    name: "description",
                    field: "description",
                    align: "left",
                },
                { label: "Slug", name: "slug", field: "slug", align: "left" },
                {
                    label: "System",
                    name: "system",
                    field: "system",
                    align: "left",
                },
                {
                    label: "Actions",
                    name: "actions",
                    field: "actions",
                    align: "center",
                },
            ],
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
