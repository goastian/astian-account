<template>
    <el-button type="success" @click="showModal(scope)"> Update </el-button>

    <el-dialog
        v-model="show_modal"
        title="Panel to update scopes"
        draggable
        destroy-on-close
        append-to-body
    >
        <div class="row">
            <div class="col">
                <el-input placeholder="Scope" v-model="form.scope"></el-input>
                <v-error :error="errors.scope"></v-error>
            </div>
            <div class="col">
                <el-input
                    v-model="form.description"
                    placeholder="Short description"
                    type="textarea"
                />

                <v-error :error="errors.description"></v-error>
            </div>
            <div class="col">
                <el-checkbox v-model="form.public"
                    >Make available for all users (Public Scope)</el-checkbox
                >
                <v-error :error="errors.public"></v-error>
            </div>
            <div class="col">
                <el-checkbox v-model="form.required_payment"
                    >Required payement</el-checkbox
                >
                <v-error :error="errors.required_payment"></v-error>
            </div>
        </div>
        <template #footer>
            <div class="dialog-footer">
                <el-button type="success" @click="update(form)"
                    >Update</el-button
                >
                <el-button type="warning" @click="close">Close</el-button>
            </div>
        </template>
    </el-dialog>
</template>
<script>
import { ElMessage } from "element-plus";

export default {
    props: ["scope"],

    data() {
        return {
            errors: {},
            form: {},
            show_modal: false,
        };
    },

    methods: {
        /**
         * show modal
         */
        showModal(scope) {
            this.form = scope;
            this.show_modal = !this.show_modal;
        },

        /**
         * clean keys
         */
        close() {
            this.errors = {};
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

        /**
         * update role
         * @param scope
         */
        async update(scope) {
            try {
                const res = await this.$server.put(
                    scope.links.update,
                    this.form
                );

                if ([201, 200].includes(res.status)) {
                    this.popup("Role updated successful", "success");
                    this.errors = {};
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.status == 422 &&
                    e.response.data.errors
                ) {
                    this.errors = e.response.data.errors;
                }

                if (e.response.status == 403 && e.response.data.message) {
                    this.popup(e.response.data.message, "warning");
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
