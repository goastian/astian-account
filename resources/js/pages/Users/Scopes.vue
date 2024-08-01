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
            <div
                class="item"
                v-for="(item, index) in roles"
                v-show="!item.public"
            >
                <div class="box">
                    <v-confirm
                        bg="btn-none"
                        btn=""
                        @is-clicked="confirmAction(item)"
                        @is-confirmed="addOrRemoveScope(item.id)"
                        @is-cancel="cancelAction(item.id)"
                    >
                        <template v-slot:button>
                            <input
                                :id="item.id"
                                :value="item.id"
                                type="checkbox"
                                class="user_roles"
                            />
                        </template>
                        <template v-slot:head> Alert Scopes </template>
                        <template v-slot:body>
                            <p v-html="text_body"></p>
                        </template>
                    </v-confirm>
                    <label class="label" :for="item.id">
                        <span class="fw-bold">{{ item.scope }}:</span>
                        {{ item.description }}
                    </label>
                </div>
            </div>
        </div>

        <template #footer>
            <div class="dialog-footer">
                <el-button @click="show_modal = false">Close</el-button>
            </div>
        </template>
    </el-dialog>
</template>
<script>
import { ElMessage } from "element-plus";

export default {
    emits: ["success"],

    props: ["user"],

    data() {
        return {
            show_modal: false,
            errors: {},
            roles: {},
            text_body: {},
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

        /**
         * Show a windows confirmation
         * @param item
         */
        confirmAction(item) {
            const checked = document.getElementById(item.id).checked;
            this.text_body = checked
                ? `The next role <strong>${item.scope}</strong> will be removed`
                : `The next role <strong>${item.scope}</strong> will be added`;
        },

        /**
         * Add or remove roles
         * @param id
         */
        addOrRemoveScope(id) {
            this.popup(null);
            this.status = null;
            const checked = document.getElementById(id).checked;

            if (checked) {
                this.addRoles(id);
            } else {
                this.removeRoles(id);
            }
        },

        /**
         * Cancel operation
         */
        cancelAction(id) {
            var role = document.getElementById(id);
            role.checked = !role.checked;
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
                        `A new Scope <strong> (${res.data.data.scope}) </strong> added`,
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
                        `The Scope <strong> ${res.data.data.scope} </atrong> was removed`,
                        "success"
                    );
                }
            } catch (e) {
                if (e.response && e.response.status == 403) {
                    this.popup(e.response.data.message, "warning");
                }

                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        },

        loadData(user) {
            this.getRoles();
            this.getUserRoles(user);
        },

        async getRoles() {
            try {
                const res = await this.$server.get("/api/admin/roles");

                if (res.status == 200) {
                    this.roles = res.data.data;
                }
            } catch (e) {}
        },

        /**
         * Obtiene los roles de un usuario
         */
        async getUserRoles(item) {
            try {
                this.popup(null);
                const res = await this.$server.get(item.links.roles);

                if (res.status == 200) {
                    this.role_selected(res.data.data);
                }
            } catch (e) {}
        },

        /**
         * selecciona los roles del usuario
         * @param {*} objetos
         */
        role_selected(objetos) {
            const roles = document.querySelectorAll(".user_roles");

            for (let i = 0; i < roles.length; i++) {
                roles[i].checked = false;
            }

            for (let i = 0; i < objetos.length; i++) {
                for (let j = 0; j < roles.length; j++) {
                    if (roles[j].value == objetos[i]["id"]) {
                        roles[j].checked = true;
                    }
                }
            }
        },
    },
};
</script>
<style lang="scss" scoped>
.user-scopes {
    display: flex;
    flex-wrap: wrap;

    .item {
        flex: 1 1 calc(100% / 2);
        margin-bottom: 0.5em;

        .box {
            display: flex;

            label {
                margin-left: 1%;
            }
        }
    }
}

.hide {
    display: none;
}

.message {
    display: block;
    border-top: 1px solid var(--border-color-light);
    padding: 1em;
    text-align: center;
    color: var(--first-color);
}
</style>
