<template>
    <v-sheet class="px-3">
        <v-data-table
            :items-per-page="search.per_page"
            :headers="headers"
            :items="clients"
        >
            <template v-slot:top>
                <div class="flex mx-3 justify-between align-center">
                    <h1 class="fw-bold">List of users</h1>
                    <v-create @created="getClients()"></v-create>
                </div>
            </template>
            <template #item.id="{ item }">
                <v-chip
                    :key="item.id"
                    @click="copyToClipboard(item.id)"
                    class="ma-2"
                    color="primary"
                    dark
                >
                    *******
                </v-chip>
                <v-snackbar v-model="snackbar" :timeout="2000">
                    Copied to clipboard
                </v-snackbar>
            </template>
            <template #item.secret="{ item }">
                <v-chip
                    v-if="item.secret"
                    :key="item.secret"
                    @click="copyToClipboard(item.secret)"
                    class="ma-2"
                    color="primary"
                    dark
                >
                    ******
                </v-chip>
                <v-snackbar v-model="snackbar" :timeout="2000">
                    Copied to clipboard
                </v-snackbar>
            </template>
            <template #item.revoked="{ item }">
                <v-chip>
                    {{ item.revoked ? "Yes" : "No" }}
                </v-chip>
            </template>
            <template #item.actions="{ item }">
                <div class="flex justify-between">
                    <v-delete @deleted="getClients" :item="item"></v-delete>
                    <v-update @updated="getClients" :item="item"></v-update>
                </div>
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
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
    },

    data() {
        return {
            clients: [],
            headers: [
                { title: "Name", value: "name", align: "start" },
                { title: "Identifier", value: "id", align: "start" },
                { title: "Secret", value: "secret", align: "start" },
                { title: "Revoked", value: "revoked", align: "start" },
                { title: "Created", value: "created_at", align: "start" },
                { title: "Updated", value: "updated_at", align: "start" },
                { title: "Actions", value: "actions", align: "center" },
            ],
            search: {
                page: 1,
                per_page: 50,
                total_pages: 0,
            },
            snackbar: false,
        };
    },

    created() {
        this.getClients();
    },

    methods: {
        getClients() {
            this.$server
                .get("/oauth/clients")
                .then((res) => {
                    this.clients = res.data.data;
                    this.search = res.data.meta;
                })
                .catch((e) => {});
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.snackbar = true;
            } catch (err) {}
        },
    },
};
</script>
