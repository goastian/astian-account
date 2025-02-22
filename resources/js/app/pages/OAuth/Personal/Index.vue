<template>
    <v-sheet class="px-3">
        <v-data-table
            :items-per-page="search.per_page"
            :headers="headers"
            :items="tokens"
        >
            <template v-slot:top>
                <div class="flex mx-3 justify-between align-center">
                    <h1 class="fw-bold">List of users</h1>
                    <v-create @created="getPersonalAccessToken()"></v-create>
                </div>
            </template>
            <template #item.actions="{ item }">
                <v-delete
                    @deleted="getPersonalAccessToken"
                    :item="item"
                ></v-delete>
            </template>
            <template v-slot:bottom>
                <v-pagination
                    v-model="search.page"
                    :length="search.total_pages"
                    :total-visible="7"
                ></v-pagination>
            </template>
        </v-data-table>
    </v-sheet>
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
                { title: "Name", value: "name", align: "start" },
                { title: "Created", value: "created", align: "start" },
                { title: "Expires", value: "expires", align: "start" },
                { title: "Actions", value: "actions", align: "start" },
            ],
            user_id: null,
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
                    this.search = res.data.meta;
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
