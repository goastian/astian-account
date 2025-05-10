<template>
    <v-user-layout>
        <q-toolbar>
            <q-toolbar-title> List of API KEY </q-toolbar-title>
            <v-create @created="getPersonalAccessToken()" />
        </q-toolbar>
        <div class="row q-col-gutter-md">
            <div
                class="col-12 col-md-4 col-lg-3 q-gutter-sm"
                v-for="token in tokens"
                :key="token.name"
            >
                <q-card flat bordered>
                    <q-card-section>
                        <div class="text-h6">{{ token.name }}</div>
                        <div class="text-caption text-grey">
                            Created: {{ token.created }}
                        </div>
                        <div class="text-caption text-grey">
                            Expires: {{ token.expires }}
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions align="right">
                        <v-delete
                            @deleted="getPersonalAccessToken"
                            :item="token"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </div>

        <div class="row justify-center q-mt-md">
            <q-pagination
                v-model="search.per_page"
                color="grey-8"
                :max="pages.total_pages"
                size="sm"
            />
        </div>
    </v-user-layout>
</template>
<script>
import VUserLayout from "../../UserLayout.vue";
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";

export default {
    components: {
        VCreate,
        VDelete,
        VUserLayout,
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
        const values = this.$page.props.tokens;
        this.tokens = values.data;
        this.pages = values.meta.pagination;
    },

    methods: {
        getPersonalAccessToken() {
            this.$server
                .get(this.$page.props.route)
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
