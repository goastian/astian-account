<template>
    <el-button type="info" @click="showModal(user)"> Scopes </el-button>

    <el-dialog
        v-model="show_modal"
        title="Manage scopes"
        draggable
        destroy-on-close
        append-to-body
        fullscreen
    >
        <div class="user-scopes">
            <el-checkbox-group v-model="selected_roles">
                <el-checkbox
                    v-for="(item, index) in roles"
                    :key="index"
                    v-show="!item.public"
                    :value="item.id"
                    @change="confirm(item, $event)"
                >
                    <strong>{{ item.scope }}: </strong>
                    <span>{{ item.description }}</span>
                </el-checkbox>
            </el-checkbox-group>
        </div>
        <template #footer>
            <div class="dialog-footer">
                <el-button @click="show_modal = false">Close</el-button>
            </div>
        </template>
    </el-dialog>
</template>
<script>
import { ElMessage, ElMessageBox } from "element-plus";
export default {
    emits: ["success"],

    props: ["user"],

    data() {
        return {
            show_modal: false,
            errors: {},
            roles: {},
            selected_roles: [],
        };
    },

    methods: {
        /**
         * show modal
         */
        showModal(user) {
            this.loadData(user);
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

        confirm(role, event) {
            ElMessageBox.confirm(
                event
                    ? `The next role (${role.scope}) will be added `
                    : `The next role (${role.scope}) will be deleted`,
                "Warning",
                {
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancel",
                    type: "warning",
                }
            )
                .then(() => {
                    if (event) {
                        this.addRoles(role.id);
                    } else {
                        this.removeRoles(role.id);
                    }
                })
                .catch(() => {
                    //the checked is true delete role
                    if (event) {
                        this.selected_roles = this.selected_roles.filter(
                            (id) => id !== role.id
                        );
                    } else {
                        //the checked is false add role
                        this.selected_roles.push(role.id);
                    }
                });
        },

        /**
         * Add roles
         */
        async addRoles(id) {
            try {
                const res = this.$server.post(this.user.links.roles, {
                    scope_id: id,
                });

                if (res.status == 422) {
                    this.popup(
                        `A new Scope  (${res.data.data.scope}) added successfully.`,
                        "warning"
                    );
                }
            } catch (e) {
                if (e.response && e.response.data.status == 403) {
                    this.popup(e.response.data.errors.message, "warning");
                }
                if (e.response && e.response.status == 422) {
                    this.popup(e.response.data.message, "warning");
                }
            }
        },

        async removeRoles(id) {
            try {
                const res = await this.$server.delete(
                    `${this.user.links.roles}/${id}`
                );

                if (res.status == 200) {
                    this.popup(
                        `The Scope ${res.data.data.scope} deleted successfully`,
                        "success"
                    );
                }
            } catch (e) {
                if (e.response && e.response.status == 403) {
                    this.popup(e.response.data.message, "warning");
                    this.selected_roles.push(id);
                }

                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        },

        /**
         * Load data
         */
        loadData(user) {
            this.getRoles();
            this.getUserRoles(user);
        },

        /**
         * Get roles
         */
        async getRoles() {
            try {
                const res = await this.$server.get("/api/admin/roles");

                if (res.status == 200) {
                    this.roles = res.data.data;
                }
            } catch (e) {}
        },

        /**
         * Get user roles
         */
        async getUserRoles(item) {
            try {
                const res = await this.$server.get(item.links.roles);

                if (res.status == 200) {
                    this.selected_roles = res.data.data.map((role) => role.id);
                }
            } catch (e) {}
        },
    },
};
</script>
<style lang="scss" scoped>
.user-scopes {
    display: flex;
    flex-wrap: wrap;

    .el-checkbox-group {
        display: flex;
        flex-wrap: wrap;

        .el-checkbox {
            flex: 1 1 calc(95% / 2);
            margin-right: 30px;
        }
    }
}
</style>
