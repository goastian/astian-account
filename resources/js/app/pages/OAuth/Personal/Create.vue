<template>
    <el-button type="primary" @click="showModal"> Token Generate </el-button>

    <el-dialog
        v-model="show_modal"
        title="Panel to generate personal tokens"
        draggable
        destroy-on-close
        append-to-body
        fullscreen
    >
        <div class="row">
            <div class="col">
                <el-input placeholder="Token Name" v-model="name" />
                <v-error :error="errors.name"></v-error>
            </div>

            <div class="col">
                <el-checkbox-group v-model="scopes_selected">
                    <el-checkbox
                        v-for="(item, index) in scopes"
                        :key="index"
                        :label="item.id + ' ' + item.description"
                        :value="item.id"
                    />
                </el-checkbox-group>
            </div>
            <div class="col">
                <el-button type="success" @click="createToken">
                    Token Generate
                </el-button>
            </div>

            <div class="col" v-if="tokens.accessToken">
                <label for="">Access token generated</label>
                <el-input
                    type="textarea"
                    v-model="tokens.accessToken"
                ></el-input>
            </div>
        </div>
        <template #footer>
            <div class="dialog-footer">
                <el-button type="warning" @click="close">Close</el-button>
            </div>
        </template>
    </el-dialog>
</template>
<script>
import { ElMessage } from "element-plus";

export default {
    emits: ["created"],

    data() {
        return {
            name: "",
            tokens: {},
            errors: {},
            scopes: {},
            scopes_selected: [],
            checked: null,
            show_modal: false,
        };
    },

    mounted() {
        this.listenEvent();
    },

    methods: {
        showModal() {
            this.getScopes();
            this.token = null;
            this.errors = {};
            this.scopes = {};
            this.scopes_selected = [];
            this.show_modal = !this.show_modal;
        },

        close() {
            this.token = null;
            this.errors = {};
            this.scopes = {};
            this.scopes_selected = [];
            this.show_modal = !this.show_modal;
        },

        /**
         * message
         */
        popup(message, type = "success") {
            if (message) {
                ElMessage({
                    message: message,
                    type: type,
                });
            }
        },

        async createToken() {
            try {
                const res = await this.$server.post(
                    "/oauth/personal-access-tokens",
                    {
                        name: this.name,
                        scopes: this.scopes_selected,
                    }
                );

                if (res.status == 200) {
                    this.tokens = res.data;
                    this.errors = {};
                    this.scopes_selected = [];
                    this.name = null;
                    this.popup("New token generated successfully");
                    this.$emit("created");
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        },

        getScopes() {
            this.$server
                .get("/api/oauth/scopes")
                .then((res) => {
                    this.scopes = res.data;
                })
                .catch((e) => {});
        },

        listenEvent() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen(".StoreRoleEvent", (e) => {
                    this.getScopes();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen(".UpdateRoleEvent", (e) => {
                    this.getScopes();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen(".DestroyRoleEvent", (e) => {
                    this.getScopes();
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.row {
    display: flex;
    flex-wrap: wrap;

    .col {
        flex: 1 1 calc(100% / 2);
        padding: 0.5em;

        &:nth-child(2) {
            flex: 1 1 95%;

            .el-checkbox-group {
                display: flex;
                flex-wrap: wrap;

                .el-checkbox {
                    flex: 1 1 calc(95% / 3) !important;
                    padding: 0.2em;
                }
            }
        }
    }
}
</style>
