<template>
    <q-table
        flat
        bordered
        :rows="tokens"
        :columns="headers"
        row-key="name"
        hide-bottom
        :rows-per-page-options="[search.per_page]"
        hide-pagination
    >
        <template v-slot:top>
            <h6>List of API KEY</h6>
            <q-space />
            <v-create @created="getPersonalAccessToken()"></v-create>
        </template>

        <template v-slot:body-cell-actions="props">
            <q-td>
                <v-delete
                    @deleted="getPersonalAccessToken"
                    :item="props.row"
                ></v-delete>
            </q-td>
        </template>
    </q-table>

    <div class="row justify-center q-mt-md">
        <q-pagination
            v-model="search.per_page"
            color="grey-8"
            :max="pages.total_pages"
            size="sm"
        />
    </div>
</template>
<script>
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";

export default {
    components: {
        VCreate,
        VDelete,
    },

    data() {
        return {
            tokens: [],
            headers: [
                { label: "Name", field: "name", align: "left" },
                { label: "Created", field: "created", align: "left" },
                { label: "Expires", field: "expires", align: "left" },
                {
                    label: "Actions",
                    name: "actions",
                    field: "actions",
                    align: "left",
                },
            ],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 50,
                total_pages: 0,
            },
        };
    },

    created() {
        this.getPersonalAccessToken();
    },

    methods: {
        getPersonalAccessToken() {
            this.$server
                .get("/oauth/api-keys")
                .then((res) => {
                    this.tokens = res.data.data;
                    const meta = res.data.meta;
                    this.pages = res.data.meta.pagination;
                    this.search.total_pages =
                        res.data.meta.pagination.total_pages;
                })
                .catch((e) => {});
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_1(this.$id))
                .listen("RevokeCredentialsEvent", (e) => {
                    this.getPersonalAccessToken();
                });
        },
    },
};
</script>
