<template>
    <el-button type="primary" @click="showModal">
        Panel to add new channels
    </el-button>

    <el-dialog
        v-model="show_modal"
        title="Panel to add channels"
        draggable
        destroy-on-close
        append-to-body
    >
        <div class="row">
            <div class="col">
                <el-input v-model="form.channel" placeholder="Channel" />
                <v-error :error="errors.channel"></v-error>
            </div>
            <div class="col">
                <el-input
                    type="textarea"
                    v-model="form.description"
                    placeholder="Description"
                />

                <v-error :error="errors.description"></v-error>
            </div>
        </div>
        <template #footer>
            <div class="dialog-footer">
                <el-button type="success" @click="storeBroadcast"
                    >Register</el-button
                >
                <el-button type="warning" @click="close">Close</el-button>
            </div>
        </template>
    </el-dialog>
</template>
<script>
import { ElMessage } from "element-plus";

export default {
    data() {
        return {
            form: {},
            errors: {},
            show_modal: false,
        };
    },

    methods: {
        showModal() {
            this.show_modal = !this.show_modal;
        },

        close() {
            this.form = {};
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

        async storeBroadcast() {
            try {
                const res = await this.$server.post(
                    "/api/broadcasts",
                    this.form
                );

                if (res.status == 201) {
                    this.form = {};
                    this.errors = {};
                    this.popup("New channels has been created.", "success");
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.popup(e.response.data.errors, "warning");
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
