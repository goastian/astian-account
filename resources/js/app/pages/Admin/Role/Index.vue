<template>
    <v-filter :params="params" @change="searching" />
    <q-toolbar class="q-ma-sm">
        <q-toolbar-title> List of roles </q-toolbar-title>

        <v-create @created="getGroups"></v-create>
    </q-toolbar>
    <div class="row q-col-gutter-md q-ma-sm">
        <div
            class="col-xs-12 col-sm-6 col-md-4"
            v-for="role in roles"
            :key="role.id"
        >
            <q-card flat bordered>
                <q-card-section class="q-pb-none">
                    <div class="text-h6 text-ucfirst">{{ role.name }}</div>
                    <div class="text-subtitle2 text-grey-7">
                        {{ role.slug }}
                    </div>
                </q-card-section>

                <q-card-section>
                    <div class="text-body2 line-clamp-2">
                        {{ role.description }}
                    </div>
                </q-card-section>

                <q-card-section class="text-caption text-grey-8 q-pt-none">
                    System: <strong>{{ role.system ? "Yes" : "No" }}</strong>
                </q-card-section>

                <q-separator />

                <q-card-actions align="right">
                    <v-update @updated="getRoles" :item="role" />
                    <v-delete
                        v-if="!role.system"
                        @deleted="getRoles"
                        :item="role"
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
            roles: [],
            params: [{ key: "Name", value: "name" }],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            snackbar: false,
        };
    },

    watch: {
        "search.page"(value) {
            this.getRoles();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getRoles();
            }
        },
    },

    created() {
        this.getRoles();
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getRoles(event);
        },

        getRoles(param = null) {
            var params = {};
            Object.assign(params, this.search);
            Object.assign(params, param);

            this.$server
                .get("/api/admin/roles", {
                    params: params,
                })
                .then((res) => {
                    this.roles = res.data.data;
                    var meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch((e) => {});
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$q.notify({
                    type: "positive",
                    message: "Copy successfully",
                    timeout: 3000,
                });
            } catch (err) {}
        },
    },
};
</script>
