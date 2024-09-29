<template>
    <el-button type="primary" @click="showModal">
       Add
    </el-button>

    <el-dialog
        v-model="show_modal"
        title="Panel to register new clients"
        draggable
        destroy-on-close
        append-to-body
    >
        <div class="row">
            <div class="col">
                <el-input
                    v-model="client.name"
                    placeholder="Application Name"
                />
                <v-error :error="errors.name"></v-error>
            </div>

            <div class="col">
                <el-input
                    v-model="client.redirect"
                    placeholder="https://app.dominio.dom/callback"
                />
                <v-error :error="errors.redirect"></v-error>
            </div>

            <div class="col">
                <el-checkbox v-model="client.confidential">
                    Private Client (<strong>By default Public Client</strong>)
                </el-checkbox>
            </div>
        </div>
        <template #footer>
            <div class="dialog-footer">
                <el-button type="success" @click="storeClients"
                    >Register</el-button
                >
                <el-button type="warning" @click="close">Close</el-button>
            </div>
        </template>
    </el-dialog>
</template>
<script>
import { ElMessage } from 'element-plus';

export default {
    emits: ["created"],

    data() {
        return {
            client: {
                name: null,
                redirect: null,
                confidential: false,
            },
            errors: {},
            show_modal: false,
        };
    },

    methods: {
        showModal() {
            this.show_modal = !this.show_modal;
        },

        close() {
            this.client = {
                name: null,
                redirect: null,
                confidential: false,
            };
            this.errors = {};
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

        async storeClients() {
            try {
                const res = await this.$server.post("/oauth/clients", this.client);

                if (res.status == 201) {
                    this.client = {};
                    this.errors = {};
                    this.popup("New client has been registered")
                    this.$emit("created")
                }
            } catch (e) {
                if (e.response && e.response.data.errors) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>
<style lang="scss" scoped>
.row {
    display: flex;
    flex-wrap: wrap;

    .col {
        flex: 1 1 100%;
        margin-bottom: 0.5em;
    }
}
</style>
