<template>
    <el-card>
        <template #header>
            <div class="card-header">
                <span>Send notifications</span>
            </div>
        </template>
        <div class="row">
            <div class="col">
                <label>Select method</label>
                <el-select v-model="form.method" placeholder="Select">
                    <el-option label="Application" value="database" />
                    <el-option label="Email" value="mail" />
                </el-select>
                <v-error :error="errors.method"></v-error>
            </div>
            <div class="col">
                <label for="">Subject</label>
                <el-input placeholder="Subject" v-model="form.title" />
            </div>
            <div class="col">
                <label for="">Message</label>
                <el-input
                    v-model="form.message"
                    placeholder="Wrtie a message"
                    type="textarea"
                    :rows="5"
                />
                <v-error :error="errors.message"></v-error>
            </div>
            <div class="col">
                <label for="">Resources</label>
                <el-input placeholder="https://site.com" v-model="form.resource" />
                <v-error :error="errors.resource"></v-error>
            </div>
            <div class="col">
                <label for="">Write user or select scopes</label>
                <el-select
                    v-model="form.scope"
                    multiple
                    filterable
                    allow-create
                    default-first-option
                    :reserve-keyword="false"
                    placeholder="Choose or write an emails"
                >
                    <el-option
                        v-for="item in scopes"
                        :key="item.id"
                        :label="item.scope + ' ' + item.description"
                        :value="item.scope"
                    />
                </el-select>
                <v-error :error="errors.scope"></v-error>
            </div>
        </div>
        <template #footer>
            <el-button type="primary" @click="send"> send </el-button>
        </template>
    </el-card>
</template>
<script>
import { ElMessage } from "element-plus";

export default {
    data() {
        return {
            form: {},
            message: null,
            errors: {},
            scopes: [],
            pages: {},
            search: {
                page: 1,
            },
        };
    },

    mounted() {
        this.getScopes();
    },

    methods: {
        close() {
            this.message = null;
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

        async getScopes() {
            try {
                const res = await this.$server.get("/api/admin/roles", {
                    params: this.search,
                });

                if (res.status == 200) {
                    this.scopes = res.data.data;
                }
            } catch (e) {
                if (e.response && e.response.status == 403) {
                    this.popup(e.response.data.message, "warning");
                }
            }
        },

        send() {
            this.$server
                .post("/api/notifications/push", this.form)
                .then((res) => {
                    this.errors = {};
                    this.form = {};
                    this.message = res.data.message;
                })
                .catch((err) => {
                    if (err.response && err.response.data.errors) {
                        this.errors = err.response.data.errors;
                    }

                    if (err.response && err.response.status == 403) {
                        this.message = err.response.data.message;
                    }
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
        flex: 1 1 95%;
        padding: 0.2em;
    }
}
</style>
