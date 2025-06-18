<template>
    <div>
        <q-btn outline icon="mdi-shield-lock-open-outline" color="positive" @click="open">
            <q-tooltip transition-show="rotate" transition-hide="rotate">
                Manage scopes
            </q-tooltip>
        </q-btn>

        <q-dialog v-model="dialog" full-width persistent>
            <q-card class="q-pa-md">
                <q-card-section>
                    <q-toolbar class="q-ma-sm">
                        <q-toolbar-title>
                            Manage scopes for
                            {{ service.name }}
                        </q-toolbar-title>

                        <v-add-scope :link="service.links.scopes" @created="getScopes"></v-add-scope>
                    </q-toolbar>
                </q-card-section>

                <q-card-section class="q-gutter-md">
                    <transition-group name="fade" tag="div" class="row q-col-gutter-md">
                        <div v-for="(item, index) in scopes" :key="index" class="col-12 col-md-3 col-lg-4">
                            <q-card class="bg-grey-1 q-hoverable" bordered flat :elevation="2">
                                <q-card-section>
                                    <q-toolbar class="q-ma-sm">
                                        <q-toolbar-title>
                                            <div class="text-subtitle2 text-primary">
                                                <q-icon name="mdi-lock-check-outline" class="q-mr-sm" />
                                                {{ item.role.name }}
                                            </div>
                                            <div class="text-caption text-grey-7">
                                                <q-icon name="groups" class="q-mr-sm" />
                                                {{ item.service.group.name }}
                                            </div>
                                        </q-toolbar-title>
                                    </q-toolbar>
                                </q-card-section>

                                <q-separator />

                                <q-card-section class="q-pt-sm">
                                    <div class="text-caption">
                                        <strong>GSR_ID:</strong>
                                        <q-chip clickable @click="copyToClipboard(item.gsr_id)" color="green"
                                            text-color="white" icon="mdi-content-copy" :label="item.gsr_id">
                                            <q-tooltip>Copy</q-tooltip>
                                        </q-chip>
                                    </div>
                                    <div class="text-caption">
                                        <strong>API KEY:</strong>
                                        <q-badge :color="item.api_key ? 'green' : 'red'
                                            " outline class="q-ml-sm">
                                            {{ item.api_key ? "Yes" : "No" }}
                                        </q-badge>
                                    </div>
                                    <div class="text-caption q-mt-sm">
                                        <strong>Active:</strong>
                                        <q-badge :color="item.active ? 'green' : 'red'
                                            " outline class="q-ml-sm">
                                            {{ item.active ? "Yes" : "No" }}
                                        </q-badge>
                                    </div>
                                    <div class="text-caption">
                                        <strong>Public:</strong>
                                        <q-badge :color="item.public ? 'blue' : 'grey'
                                            " outline class="q-ml-sm">
                                            {{ item.public ? "Yes" : "No" }}
                                        </q-badge>
                                    </div>
                                </q-card-section>

                                <q-card-actions class="flex justify-between q-pa-sm flex-wrap">
                                    <v-delete-scope :scope="item" @deleted="getScopes"></v-delete-scope>
                                    <v-add-scope icon="mdi-pencil-lock-outline" :scope="item"
                                        :link="service.links.scopes" @created="getScopes"></v-add-scope>
                                </q-card-actions>
                            </q-card>
                        </div>
                    </transition-group>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn outline label="Close" color="positive" v-close-popup />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
import VAddScope from "./AddScope.vue";
import VDeleteScope from "./DeleteScope.vue";

export default {
    props: ["service"],

    components: {
        VAddScope,
        VDeleteScope,
    },

    data() {
        return {
            scopes: {},
            dialog: false,
        };
    },

    methods: {
        open() {
            this.getScopes();
            this.dialog = true;
        },

        async getScopes() {
            try {
                const res = await this.$server.get(this.service.links.scopes);

                if (res.status == 200) {
                    this.scopes = res.data.data;
                }
            } catch (e) { }
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$q.notify({
                    type: "positive",
                    message: "Copy successfully",
                    timeout: 3000,
                });
            } catch (err) { }
        },
    },
};
</script>
