<template>
    <v-filter :params="params" @change="searching" />

    <q-toolbar class="q-ma-sm">
        <q-toolbar-title> List of groups </q-toolbar-title>

        <v-create @created="getGroups"></v-create>
    </q-toolbar>

    <div class="row q-col-gutter-md q-ma-sm">
        <div
            class="col-xs-12 col-sm-6 col-md-4"
            v-for="group in groups"
            :key="group.id"
        >
            <q-card flat bordered>
                <q-card-section class="q-pb-none">
                    <div class="text-h6 text-ucfirst">{{ group.name }}</div>
                    <div class="text-subtitle2 text-grey-7">
                        {{ group.slug }}
                    </div>
                </q-card-section>

                <q-card-section>
                    <div class="line-clamp-2">
                        {{ group.description }}
                    </div>
                </q-card-section>

                <q-card-section class="text-caption text-grey-8">
                    System: <strong>{{ group.system ? "Yes" : "No" }}</strong>
                </q-card-section>

                <q-separator />

                <q-card-actions align="right">
                    <v-update @updated="getGroups" :item="group" />
                    <v-delete
                        v-if="!group.system"
                        @deleted="getGroups"
                        :item="group"
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
            groups: [],
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
            this.getGroups();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getGroups();
            }
        },
    },

    created() {
        this.getGroups();
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getGroups(event);
        },

        getGroups(param = null) {
            var params = {};
            Object.assign(params, this.search);
            Object.assign(params, param);

            this.$server
                .get("/api/admin/groups", {
                    params: params,
                })
                .then((res) => {
                    this.groups = res.data.data;
                    var meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch((e) => {});
        },
    },
};
</script>
<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
