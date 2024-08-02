<template>
    <el-button type="success" @click="showModal(client)"> update </el-button>

    <el-dialog
        v-model="show_modal"
        title="Panel to update client"
        draggable
        destroy-on-close
        append-to-body
    >
        <div class="row">
            <div class="col">
                <label for="">Client</label>
                <el-input
                    v-model="client.name"
                    placeholder="Application Name"
                />
                <v-error :error="errors.name"></v-error>
            </div>

            <div class="col">
                <label for="">Redirect</label>
                <el-input
                    v-model="client.redirect"
                    placeholder="https://app.dominio.dom/callback"
                />
                <v-error :error="errors.redirect"></v-error>
            </div>
        </div>
        <template #footer>
            <div class="dialog-footer">
                <el-button type="success" @click="updateClient(client)"
                    >Update</el-button
                >
                <el-button type="warning" @click="close">Close</el-button>
            </div>
        </template>
    </el-dialog>
</template>
<script>
import { ElMessage } from 'element-plus';

export default {
    props: {
        client: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            errors: {
                name: "",
                redirect: "",
            },
            form: {},
            show_modal: false,
        };
    },

    methods: {
        showModal(client) {
            this.form = client;
            this.show_modal = !this.show_modal;
        },

        close() {
            this.form = {};
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

        async updateClient(client) {
            try {
                const res = await this.$server.put(
                    "/oauth/clients/" + client.id,
                    this.client
                );

                if (res.status == 200) {
                    this.popup("Client has been updated.");
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
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
