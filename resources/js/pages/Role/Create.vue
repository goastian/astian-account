<template>
    <el-button type="primary" @click="showModal">
        Panel to add new roles
    </el-button>

    <el-dialog
        v-model="show_modal"
        title="Panel to create new scopes"
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
                <el-button type="success" @click="create">Register</el-button>
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
            errors: {},
            show_modal: false,
            form: {
                scope: null,
                description: null,
                public: false,
                required_payment: false,
            },
        };
    },

    methods: {
        /**
         * Show the modal in the windowss
         */
        showModal() {
            this.show_modal = !this.show_modal;
        },

        /**
         * cleen keys
         */
        close() {
            this.show_modal = !this.show_modal;
            this.form = {
                public: false,
                required_payment: false,
            };
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
         * create a new role
         */
        async create() {
            try {
                const res = await this.$server.post(
                    "/api/admin/roles",
                    this.form
                );

                if (res.status == 201) {
                    this.errors = {};
                    this.form = {
                        public: false,
                        required_payment: false,
                    };

                    this.popup(
                        "New scope has been created successful",
                        "success"
                    );
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
